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
 * German language strings for block_cardgrid.
 *
 * @package   block_cardgrid
 * @copyright 2026 Benjamin Abicht
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin-Name in der Block-Auswahl und Administration.
$string['pluginname'] = 'Kartenraster';

// ── Rechte ───────────────────────────────────────────────────────────────────
$string['cardgrid:addinstance']          = 'Einen neuen Kartenraster-Block hinzufügen';
$string['cardgrid:myaddinstance']        = 'Einen neuen Kartenraster-Block zum Dashboard hinzufügen';
$string['cardgrid:setcohortrestriction'] = 'Einzelne Karten auf eine Kohorte beschränken';

// ── Allgemeine Einstellungen ─────────────────────────────────────────────────
$string['globalsettings']    = 'Raster-Einstellungen';
$string['blocktitle']        = 'Blocktitel';
$string['blocktitle_hint']   = 'Leer lassen, um die Titelleiste auszublenden.';

$string['columns']   = 'Spaltenanzahl';
$string['columns_2'] = '2 Spalten';
$string['columns_3'] = '3 Spalten';
$string['columns_4'] = '4 Spalten';

// ── Felder je Karte ──────────────────────────────────────────────────────────
$string['card_header']   = 'Karte {$a}';
$string['card_title']    = 'Titel';
$string['card_desc']     = 'Beschreibung';
$string['card_url']      = 'Link (URL)';
$string['card_linktext'] = 'Link-Text';

$string['card_classes']          = 'Zusätzliche CSS-Klassen';
$string['loginonly_restriction'] = 'Nur für eingeloggte Nutzer anzeigen';
$string['cohort_restriction']    = 'Auf Kohorte beschränken';
$string['cohort_none']           = 'Keine Einschränkung (für alle sichtbar)';

// ── Erweiterte Einstellungen ──────────────────────────────────────────────────
$string['advancedsettings']        = 'Erweiterte Einstellungen';
$string['show_card_advanced']      = 'Erweiterte Einstellungen pro Kachel anzeigen';
$string['show_card_advanced_hint'] = 'Schaltet für jede Kachel individuelle CSS-Klassen, die Einschränkung auf eingeloggte Nutzer und die Einschränkung auf eine globale Gruppe frei. Diese Optionen sind vor allem auf öffentlichen Seiten oder Startseiten sinnvoll.';
$string['customid']                = 'Eigene ID';
$string['customid_hint']       = 'Wenn angegeben, werden Karten-IDs als <code>cardgrid-card-{ID}-1</code> … <code>-9</code> generiert. Erlaubte Zeichen: Buchstaben, Ziffern, Bindestriche, Unterstriche. Leer lassen, um die Block-Instanz-ID zu verwenden. Wenn mehrere Kartenraster-Blöcke auf einer Seite verwendet werden, muss die ID eindeutig sein.';
$string['linkmode']             = 'Link-Darstellung';
$string['linkmode_nolink']      = 'Kein Link (URL gespeichert, aber nicht angezeigt)';
$string['linkmode_button']      = 'Nur Button';
$string['linkmode_stretchcard'] = 'Ganze Kachel verlinken';
$string['hidebutton']           = 'Link-Button ausblenden';
$string['linktext_default']     = 'Mehr';

// ── Datenschutz ──────────────────────────────────────────────────────────────
$string['privacy:metadata'] = 'Der Block „Kartenraster" speichert keine personenbezogenen Daten. Alle Konfigurationsdaten sind Inhalte auf Website- oder Kursebene, die von Administratoren oder Lehrenden eingegeben werden.';
