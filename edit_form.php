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
 * Block configuration form for block_cardgrid.
 *
 * @package   block_cardgrid
 * @copyright 2026 Benjamin Abicht
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Configuration form for a single block_cardgrid instance.
 */
class block_cardgrid_edit_form extends block_edit_form {

    /**
     * Prepopulate the form with stored config values.
     *
     * The parent implementation converts $this->block->config keys to
     * 'config_*' form defaults. Because editor elements expect an array
     * (['text' => ..., 'format' => ...]) but we store text and format as
     * separate scalar keys, we reconstruct the array here before calling
     * parent::set_data().
     *
     * @param stdClass $defaults Default values passed in by the framework.
     */
    public function set_data($defaults): void {
        if (!empty($this->block->config)) {
            // Clone the config so we can temporarily rewrite scalar desc/format pairs
            // to the array shape the editor element expects, without permanently
            // mutating the block's stored config object.
            $savedconfig = clone $this->block->config;

            for ($n = 1; $n <= block_cardgrid::MAX_CARDS; $n++) {
                $textprop   = "card{$n}_desc";
                $formatprop = "card{$n}_desc_format";

                $stored = $this->block->config->$textprop ?? '';
                if (!is_array($stored)) {
                    $format = isset($this->block->config->$formatprop)
                        ? (int)$this->block->config->$formatprop
                        : FORMAT_HTML;
                    $this->block->config->$textprop = ['text' => $stored, 'format' => $format];
                }
                unset($this->block->config->$formatprop);
            }

            parent::set_data($defaults);

            // Restore the config to its original scalar form.
            $this->block->config = $savedconfig;
        } else {
            parent::set_data($defaults);
        }
    }

    /**
     * Define all block-specific form fields.
     *
     * @param MoodleQuickForm $mform The form being built.
     */
    protected function specific_definition($mform): void {
        global $CFG, $DB;

        // ── Global settings ─────────────────────────────────────────────────

        $mform->addElement('header', 'globalsettings_header',
            get_string('globalsettings', 'block_cardgrid'));
        $mform->setExpanded('globalsettings_header');

        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_cardgrid'));
        $mform->setType('config_title', PARAM_TEXT);
        $mform->setDefault('config_title', get_string('pluginname', 'block_cardgrid'));
        $mform->addElement('static', 'config_title_hint', '',
            get_string('blocktitle_hint', 'block_cardgrid'));

        $columnopts = [
            2 => get_string('columns_2', 'block_cardgrid'),
            3 => get_string('columns_3', 'block_cardgrid'),
            4 => get_string('columns_4', 'block_cardgrid'),
        ];
        $mform->addElement('select', 'config_columns',
            get_string('columns', 'block_cardgrid'), $columnopts);
        $mform->setDefault('config_columns', 3);

        // ── Per-card settings (cards 1–9) ────────────────────────────────────

        $editoroptions = [
            'maxfiles' => 0,
            'maxbytes' => 0,
            'context'  => $this->block->context,
        ];

        // Cohort selector: only shown to users who hold the capability.
        $cancohort   = has_capability('block/cardgrid:setcohortrestriction', context_system::instance());
        $cohortsopts = null;
        if ($cancohort) {
            require_once($CFG->dirroot . '/cohort/lib.php');
            $cohortsopts = [0 => get_string('cohort_none', 'block_cardgrid')]
                + $DB->get_records_menu('cohort', null, 'name ASC', 'id, name');
        }

        for ($n = 1; $n <= block_cardgrid::MAX_CARDS; $n++) {
            $mform->addElement('header', "card{$n}_header",
                get_string('card_header', 'block_cardgrid', $n));

            // Title.
            $mform->addElement('text', "config_card{$n}_title",
                get_string('card_title', 'block_cardgrid'));
            $mform->setType("config_card{$n}_title", PARAM_TEXT);

            // Description (HTML editor).
            $mform->addElement('editor', "config_card{$n}_desc",
                get_string('card_desc', 'block_cardgrid'), null, $editoroptions);
            $mform->setType("config_card{$n}_desc", PARAM_RAW);

            // Link URL.
            $mform->addElement('text', "config_card{$n}_url",
                get_string('card_url', 'block_cardgrid'));
            $mform->setType("config_card{$n}_url", PARAM_URL);

            // Link text.
            $mform->addElement('text', "config_card{$n}_linktext",
                get_string('card_linktext', 'block_cardgrid'));
            $mform->setType("config_card{$n}_linktext", PARAM_TEXT);

            // Additional CSS classes (advanced, hidden by default).
            $mform->addElement('text', "config_card{$n}_classes",
                get_string('card_classes', 'block_cardgrid'));
            $mform->setType("config_card{$n}_classes", PARAM_TEXT);
            $mform->hideIf("config_card{$n}_classes", 'config_show_card_advanced', 'notchecked');

            // Login-only restriction (advanced, hidden by default).
            $mform->addElement('advcheckbox', "config_card{$n}_loginonly",
                get_string('loginonly_restriction', 'block_cardgrid'));
            $mform->setType("config_card{$n}_loginonly", PARAM_BOOL);
            $mform->setDefault("config_card{$n}_loginonly", 0);
            $mform->hideIf("config_card{$n}_loginonly", 'config_show_card_advanced', 'notchecked');

            // Cohort restriction (capability-gated, advanced, hidden by default).
            if ($cancohort) {
                $mform->addElement('select', "config_card{$n}_cohort",
                    get_string('cohort_restriction', 'block_cardgrid'), $cohortsopts);
                $mform->setType("config_card{$n}_cohort", PARAM_INT);
                $mform->setDefault("config_card{$n}_cohort", 0);
                $mform->hideIf("config_card{$n}_cohort", 'config_show_card_advanced', 'notchecked');
            }

            // Collapse all cards except the first by default for a cleaner UI.
            if ($n > 1) {
                $mform->setExpanded("card{$n}_header", false);
            }
        }

        // ── Advanced settings ────────────────────────────────────────────────

        $mform->addElement('header', 'advanced_header',
            get_string('advancedsettings', 'block_cardgrid'));
        $mform->setExpanded('advanced_header', false);

        $mform->addElement('advcheckbox', 'config_show_card_advanced',
            get_string('show_card_advanced', 'block_cardgrid'));
        $mform->setType('config_show_card_advanced', PARAM_BOOL);
        $mform->setDefault('config_show_card_advanced', 0);
        $mform->addElement('static', 'show_card_advanced_hint', '',
            get_string('show_card_advanced_hint', 'block_cardgrid'));

        $mform->addElement('text', 'config_customid',
            get_string('customid', 'block_cardgrid'));
        $mform->setType('config_customid', PARAM_ALPHANUMEXT);
        $mform->addElement('static', 'customid_hint', '',
            get_string('customid_hint', 'block_cardgrid'));

        $linkmodeopts = [
            'nolink'      => get_string('linkmode_nolink', 'block_cardgrid'),
            'button'      => get_string('linkmode_button', 'block_cardgrid'),
            'stretchcard' => get_string('linkmode_stretchcard', 'block_cardgrid'),
        ];
        $mform->addElement('select', 'config_linkmode',
            get_string('linkmode', 'block_cardgrid'), $linkmodeopts);
        $mform->setDefault('config_linkmode', 'button');

        $mform->addElement('advcheckbox', 'config_hidebutton',
            get_string('hidebutton', 'block_cardgrid'));
        $mform->setType('config_hidebutton', PARAM_BOOL);
        $mform->setDefault('config_hidebutton', 0);
        // Hidebutton only makes sense when the whole card is a link target.
        $mform->hideIf('config_hidebutton', 'config_linkmode', 'eq', 'nolink');
        $mform->hideIf('config_hidebutton', 'config_linkmode', 'eq', 'button');
    }

    /**
     * Validate form data.
     *
     * @param array $data  Submitted form data.
     * @param array $files Submitted files.
     * @return array Keyed by field name; empty on success.
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        for ($n = 1; $n <= block_cardgrid::MAX_CARDS; $n++) {
            $url = trim($data["config_card{$n}_url"] ?? '');
            if ($url !== '' && clean_param($url, PARAM_URL) === '') {
                $errors["config_card{$n}_url"] = get_string('invalidurl', 'error');
            }
        }

        return $errors;
    }
}
