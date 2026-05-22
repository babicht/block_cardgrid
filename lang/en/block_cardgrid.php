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
 * English language strings for block_cardgrid.
 *
 * @package   block_cardgrid
 * @copyright 2026 Benjamin Abicht
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name shown in the block chooser and administration.
$string['pluginname'] = 'Card Grid';

// ── Capabilities ─────────────────────────────────────────────────────────────
$string['cardgrid:addinstance']          = 'Add a new Card Grid block';
$string['cardgrid:myaddinstance']        = 'Add a new Card Grid block to the Dashboard';
$string['cardgrid:setcohortrestriction'] = 'Restrict individual cards to a cohort';

// ── Global settings ──────────────────────────────────────────────────────────
$string['globalsettings']    = 'Grid settings';
$string['blocktitle']        = 'Block title';
$string['blocktitle_hint']   = 'Leave empty to hide the title bar.';

$string['columns']   = 'Number of columns';
$string['columns_2'] = '2 columns';
$string['columns_3'] = '3 columns';
$string['columns_4'] = '4 columns';

// ── Per-card fields ───────────────────────────────────────────────────────────
$string['card_header']   = 'Card {$a}';
$string['card_title']    = 'Title';
$string['card_desc']     = 'Description';
$string['card_url']      = 'Link URL';
$string['card_linktext'] = 'Link text';

$string['card_classes']          = 'Additional CSS classes';
$string['loginonly_restriction'] = 'Show for logged-in users only';
$string['cohort_restriction']    = 'Restrict to cohort';
$string['cohort_none']           = 'No restriction (visible to all)';

// ── Advanced settings ─────────────────────────────────────────────────────────
$string['advancedsettings']        = 'Advanced settings';
$string['show_card_advanced']      = 'Show advanced per-card settings';
$string['show_card_advanced_hint'] = 'Enables per-card options for additional CSS classes, restricting visibility to logged-in users, and restricting to a cohort. Most useful on public pages or home pages.';
$string['customid']                = 'Custom ID';
$string['customid_hint']       = 'If set, card IDs are generated as <code>cardgrid-card-{ID}-1</code> … <code>-9</code>. Allowed characters: letters, digits, hyphens, underscores. Leave empty to use the block instance ID. The ID must be unique on the page if multiple Card Grid blocks are used.';
$string['linkmode']             = 'Link display';
$string['linkmode_nolink']      = 'No link (URL stored but not shown)';
$string['linkmode_button']      = 'Button only';
$string['linkmode_stretchcard'] = 'Whole card clickable';
$string['hidebutton']           = 'Hide link button';
$string['linktext_default']     = 'More';

// ── Privacy ──────────────────────────────────────────────────────────────────
$string['privacy:metadata'] = 'The Card Grid block does not store any personal data. All configuration is site/course-level content entered by administrators or teachers.';
