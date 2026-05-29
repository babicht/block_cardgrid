<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Renderable output class for block_cardgrid.
 *
 * @package   block_cardgrid
 * @copyright 2026 Benjamin Abicht
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_cardgrid\output;

use context;
use context_system;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Renderable data object for the card grid template.
 */
class cardgrid implements renderable, templatable {

    /**
     * @param stdClass $config     Block instance config.
     * @param context  $context    Block context (used for format_string / format_text).
     * @param int      $instanceid Block instance ID (used as fallback card ID base).
     */
    public function __construct(
        private stdClass $config,
        private context $context,
        private int $instanceid
    ) {
    }

    /**
     * Build the template data array.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        global $CFG, $USER;

        $config  = $this->config;
        $columns = isset($config->columns) ? (int)$config->columns : 3;
        $columns = in_array($columns, [2, 3, 4]) ? $columns : 3;

        switch ($columns) {
            case 2:  $colclass = 'col-md-6'; break;
            case 4:  $colclass = 'col-md-3'; break;
            default: $colclass = 'col-md-4'; break;
        }

        $customid = isset($config->customid) ? trim((string)$config->customid) : '';
        $idbase   = $customid !== ''
            ? clean_param($customid, PARAM_ALPHANUMEXT)
            : (string)$this->instanceid;

        $allowedlinkmodes = ['nolink', 'button', 'stretchcard'];
        $linkmode = isset($config->linkmode) && in_array($config->linkmode, $allowedlinkmodes)
            ? $config->linkmode : 'button';
        // hidebutton is only honoured for stretchcard mode; ignore stale stored
        // values that resulted from switching linkmode after checking the box.
        $hidebutton = $linkmode === 'stretchcard' && !empty($config->hidebutton);

        // Users who can set cohort restrictions always see every card.
        $bypasscohort = has_capability('block/cardgrid:setcohortrestriction', context_system::instance());

        // Load cohort library once if needed — only when any card uses cohort restriction.
        if (!$bypasscohort) {
            for ($i = 1; $i <= \block_cardgrid::MAX_CARDS; $i++) {
                if (!empty($config->{"card{$i}_cohort"})) {
                    require_once($CFG->dirroot . '/cohort/lib.php');
                    break;
                }
            }
        }

        $cohortmemberships = []; // Cache: cohortid => bool, avoids duplicate DB queries.
        $cards = [];

        for ($n = 1; $n <= \block_cardgrid::MAX_CARDS; $n++) {
            $title    = isset($config->{"card{$n}_title"})    ? trim((string)$config->{"card{$n}_title"})    : '';
            $url      = isset($config->{"card{$n}_url"})      ? trim((string)$config->{"card{$n}_url"})      : '';
            $linktext = isset($config->{"card{$n}_linktext"}) ? trim((string)$config->{"card{$n}_linktext"}) : '';
            $classes  = isset($config->{"card{$n}_classes"})  ? trim((string)$config->{"card{$n}_classes"})  : '';

            // Editor data can be stored as separate scalars or as an array (fallback).
            $descraw = isset($config->{"card{$n}_desc"}) ? $config->{"card{$n}_desc"} : '';
            if (is_array($descraw)) {
                $desc       = $descraw['text'] ?? '';
                $descformat = isset($descraw['format']) ? (int)$descraw['format'] : FORMAT_HTML;
            } else {
                $desc       = (string)$descraw;
                $descformat = isset($config->{"card{$n}_desc_format"})
                    ? (int)$config->{"card{$n}_desc_format"}
                    : FORMAT_HTML;
            }

            // Skip cards with no visible content.
            if ($title === '' && trim(strip_tags($desc)) === '' && $url === '') {
                continue;
            }

            // Login-only restriction: hide from guests and unauthenticated visitors.
            if (!empty($config->{"card{$n}_loginonly"}) && (!isloggedin() || isguestuser())) {
                continue;
            }

            // Cohort restriction.
            $cohortid = isset($config->{"card{$n}_cohort"}) ? (int)$config->{"card{$n}_cohort"} : 0;
            if ($cohortid > 0 && !$bypasscohort) {
                if (!isset($cohortmemberships[$cohortid])) {
                    $cohortmemberships[$cohortid] = isloggedin() && !isguestuser()
                        && cohort_is_member($cohortid, $USER->id);
                }
                if (!$cohortmemberships[$cohortid]) {
                    continue;
                }
            }

            // Sanitize URL and CSS classes.
            $url     = clean_param($url, PARAM_URL);
            $classes = preg_replace('/[^a-zA-Z0-9_\- ]/', '', clean_param($classes, PARAM_TEXT));
            $classes = trim($classes);

            $cardid    = "cardgrid-card-{$idbase}-{$n}";
            $cardclass = 'card cardgrid-card h-100' . ($classes !== '' ? ' ' . $classes : '');

            $formattedtitle = $title !== ''
                ? format_string($title, true, ['context' => $this->context])
                : '';
            $formatteddesc  = $desc !== ''
                ? format_text($desc, $descformat, ['context' => $this->context])
                : '';

            $linkdata = [];
            if ($url !== '' && $linkmode !== 'nolink') {
                $linktextout = $linktext !== ''
                    ? format_string($linktext, true, ['context' => $this->context])
                    : get_string('linktext_default', 'block_cardgrid');

                if ($hidebutton) {
                    $linkdata = [
                        'url'        => $url,
                        'linktext'   => $linktextout,
                        'btnclass'   => 'stretched-link',
                        'hidebutton' => true,
                    ];
                } else {
                    $btnclass = 'btn btn-primary mt-auto'
                        . ($linkmode === 'stretchcard' ? ' stretched-link' : '');
                    $linkdata = [
                        'url'        => $url,
                        'linktext'   => $linktextout,
                        'btnclass'   => $btnclass,
                        'hidebutton' => false,
                    ];
                }
            }

            $cards[] = [
                'cardid'    => $cardid,
                'cardclass' => $cardclass,
                'colclass'  => $colclass,
                'title'     => $formattedtitle,
                'hastitle'  => $formattedtitle !== '',
                'desc'      => $formatteddesc,
                'hasdesc'   => $formatteddesc !== '',
                'hasurl'    => $url !== '' && $linkmode !== 'nolink',
            ] + $linkdata;
        }

        return [
            'hascards' => !empty($cards),
            'cards'    => $cards,
        ];
    }
}
