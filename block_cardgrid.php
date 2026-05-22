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
 * Main block class for block_cardgrid.
 *
 * @package   block_cardgrid
 * @copyright 2026 Benjamin Abicht
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block that displays configurable cards in a Bootstrap grid.
 */
class block_cardgrid extends block_base {

    /** Maximum number of cards per block instance. */
    public const MAX_CARDS = 9;

    /**
     * Initialise the block.
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_cardgrid');
    }

    /**
     * Apply the per-instance title from config, overriding the default from init().
     */
    public function specialization(): void {
        if (isset($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }

    /**
     * Hide the block header when the title has been explicitly cleared.
     */
    public function hide_header(): bool {
        return isset($this->config->title) && trim($this->config->title) === '';
    }

    /**
     * No global admin settings for this block.
     */
    public function has_config(): bool {
        return false;
    }

    /**
     * Allow multiple instances on the same page.
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * This block can appear on any page type.
     */
    public function applicable_formats(): array {
        return ['all' => true];
    }

    /**
     * Intercept config save to split editor arrays into separate text/format fields.
     *
     * Moodle's editor element returns ['text' => ..., 'format' => ...] arrays.
     * We store them as separate scalar keys so get_content() can access them
     * without needing to know whether the value is an array or a plain string.
     *
     * @param stdClass $data   Config data from the edit form.
     * @param bool     $nolongerused  Legacy parameter, unused.
     */
    public function instance_config_save($data, $nolongerused = false) {
        $newdata = clone $data;

        $allowedformats = [FORMAT_HTML, FORMAT_MOODLE, FORMAT_PLAIN, FORMAT_MARKDOWN];

        for ($n = 1; $n <= self::MAX_CARDS; $n++) {
            $key = "card{$n}_desc";
            if (isset($newdata->$key) && is_array($newdata->$key)) {
                $editordata        = $newdata->$key;
                $newdata->$key     = $editordata['text'] ?? '';
                $formatkey         = "card{$n}_desc_format";
                $format = isset($editordata['format']) ? (int)$editordata['format'] : FORMAT_HTML;
                if (!in_array($format, $allowedformats, true)) {
                    $format = FORMAT_HTML;
                }
                $newdata->$formatkey = $format;
            }
        }

        parent::instance_config_save($newdata, $nolongerused);
    }

    /**
     * Render the block content.
     *
     * @return stdClass|null
     */
    public function get_content(): ?stdClass {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass();
        $this->content->text   = '';
        $this->content->footer = '';

        if (empty($this->config)) {
            return $this->content;
        }

        $cardgrid = new \block_cardgrid\output\cardgrid($this->config, $this->context, $this->instance->id);
        $data     = $cardgrid->export_for_template($OUTPUT);

        if (!empty($data['cards'])) {
            $this->content->text = $OUTPUT->render_from_template('block_cardgrid/cardgrid', $data);
        }

        return $this->content;
    }
}
