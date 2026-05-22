# Card Grid Block for Moodle

A configurable Moodle block that displays up to nine cards in a responsive Bootstrap grid. Each card can have a title, rich-text description, and an optional link — making it suitable for landing pages, course home pages, and the Moodle front page.

## Features

- **2 / 3 / 4 column grid** — configurable per block instance
- **Rich-text description** — full HTML editor per card
- **Flexible linking** — button only, whole-card stretched link, or no link
- **Hidden button mode** — visually-hidden link for image-only cards
- **Login-only restriction** — hide individual cards from guests and unauthenticated visitors
- **Cohort restriction** — hide individual cards from users not in a specific cohort (requires capability)
- **Custom card IDs** — anchor-link friendly, e.g. `cardgrid-card-hero-1`
- **Multiple instances** — any number of Card Grid blocks on the same page
- **Privacy compliant** — stores no personal user data (GDPR null provider)

## Requirements

- Moodle 4.5 or later
- PHP 8.1 or later

## Installation

1. Download or clone this repository into `<moodleroot>/blocks/cardgrid/`
2. Log in to Moodle as an administrator
3. Navigate to **Site administration → Notifications** — Moodle will detect the new plugin and prompt you to install it
4. Click **Upgrade Moodle database now**

## Configuration

Add the block to any page via **Edit mode → Add a block → Card Grid**.

### Grid settings

| Field | Description |
|---|---|
| Block title | Displayed in the block header. Leave empty to hide the header entirely. |
| Number of columns | 2, 3 (default), or 4 columns. |

### Per-card settings (Cards 1 – 9)

| Field | Description |
|---|---|
| Title | Card heading. |
| Description | Rich-text area for card body content. |
| Link URL | Target URL for the card link. |
| Link text | Button label. If left empty, defaults to "More". |

Cards with no title, no description text, and no URL are automatically hidden.

### Advanced per-card settings

Enable **Show advanced per-card settings** in the Advanced settings section to reveal these fields:

| Field | Description |
|---|---|
| Additional CSS classes | Extra classes added to the card element (alphanumeric, hyphens, underscores, spaces). |
| Show for logged-in users only | Hides the card from guests and unauthenticated visitors. |
| Restrict to cohort | Hides the card from users not in the selected cohort. Only available to users with the `block/cardgrid:setcohortrestriction` capability. |

### Advanced block settings

| Field | Description |
|---|---|
| Custom ID | If set, card IDs are generated as `cardgrid-card-{ID}-1` … `-9`. Useful for anchor links. Must be unique on the page when multiple Card Grid blocks are used. Allowed characters: letters, digits, hyphens, underscores. |
| Link display | **No link** — URL is stored but not rendered. **Button only** (default) renders a `btn btn-primary` button. **Whole card clickable** uses Bootstrap's `stretched-link` to make the entire card a link target. |
| Hide link button | Replaces the visible button with a visually-hidden link, keeping the card fully clickable without displaying a button. |

## Capabilities

| Capability | Default roles | Description |
|---|---|---|
| `block/cardgrid:addinstance` | editing teacher, manager | Add a Card Grid block to a course or page. |
| `block/cardgrid:myaddinstance` | manager | Add a Card Grid block to the personal Dashboard. |
| `block/cardgrid:setcohortrestriction` | manager | Configure per-card cohort restrictions. Users with this capability also bypass all cohort restrictions and see every card. |

## License

This plugin is licensed under the [GNU General Public License v3 or later](https://www.gnu.org/licenses/gpl-3.0.html).

## Author

Benjamin Abicht — 2026
