# Zorans Måleri: a lead generation WordPress site built around CRO

A lead generation website for **Zorans Måleri Roofing**, a made-up roofing company. It was built as a portfolio piece to show how I approach a lead-gen redesign in WordPress: conversion rate optimisation (CRO) basics first, a quote form that captures and tracks every lead, and old content moved over cleanly.

> Zorans Måleri Roofing is a fictional company. The copy, prices, reviews and figures are invented and labelled as samples on the site.

**Try it in your browser (no install):** [Open in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hugooblak/zoransmaleri-demo/main/blueprint.json)

Playground runs a real WordPress site in your browser tab. The first load takes a little while. You're logged in as an admin, so you can submit the form and then open **Leads** in the admin. Nothing you do there is saved.

**Tip:** click *"Show the CRO notes behind each section"* in the top bar. A short note appears above every section explaining why it's there.

![Home page](proof/screenshots/home-desktop.jpg)

---

## What the job asked for, and where to find it

| Ask | In this demo |
|---|---|
| Redesign of a lead generation site | Home page, 2 service pages, quote funnel, thank-you page, guides, FAQ, About, Privacy |
| Strong CRO fundamentals | See [CRO decisions](#cro-decisions) below, or turn on the notes on the live demo |
| Existing content formatted and added | [`content/migrate.php`](content/migrate.php) turns messy old page-builder HTML into clean blocks, with a [report](content/migration-report.md) and a [redirect list](content/redirects.csv) |
| In WordPress | Native block theme and two small plugins. No page builder |

## CRO decisions

| Where | What | Why |
|---|---|---|
| Hero | One promise (fixed price in writing), three proof points, the form's first question right there | The easiest possible first step. People who have started a form are more likely to finish it |
| First question | ZIP code only, then hand over to the full form with the ZIP filled in | Low effort to begin. It also confirms the area before asking anything personal |
| Quote funnel | Separate page with no menu and no footer links | Nothing to click away to |
| Form steps | One question per step, big choice cards that move on by themselves, progress bar, back button | Short steps feel quick. Most people only type on the last step |
| Contact details | Asked last. Each field says why it's needed ("Your written quote is sent here") | People share details more easily once they've invested, and when they know why |
| Trust | Trust strip under the hero, reviews that answer specific worries, comparison table, written guarantee | Proof sits next to every place where we ask for action |
| Price guide | Real price ranges on the home page | Price is the first question. Hiding it sends people to a competitor. It also filters out budgets that don't fit |
| Objections | FAQ built from the reasons people hesitate ("Will I get pushed to sign?") | Answers doubts before they become exits |
| Phones and tablets | Sticky "Call now / Free quote" bar. It slides away while a form is on screen | Calls are high-value leads in home services. The bar never covers the form |
| Thank-you page | Says when they'll hear back, plus a fast path for urgent leaks | Keeps the lead warm and reduces no-shows |
| Every page | Quote button in the header; phone number in the header on desktop, in the sticky bar on phones and tablets | The next step is always one click away |

Things I'd **test**, not assume, on a live site: headline wording, ZIP-first vs. service-first, price guide position, and the sticky bar. The events below make these measurable.

## Lead capture: the Zorans Måleri Leads plugin

| Feature | Detail |
|---|---|
| Quote form block | Two versions: *Start* (ZIP only, for heroes) and *Full* (4 steps). Server-rendered, so it **works without JavaScript** as a normal form |
| Leads inbox | **Leads** in the admin: name, phone (click to call), needs, timeline, ZIP, source, status. New-lead count on the menu. Only Administrators and Editors can see or export leads |
| Follow-up status | New → Contacted → Quote sent → Won / Lost, plus Suspected spam |
| CSV export | One click. Protected against spreadsheet formula injection |
| Lead source | UTM tags, Google/Facebook click IDs, landing page and referrer are recorded on every page and sent with the form. A visitor who lands on a blog post from an ad and asks for a quote later still shows that campaign |
| Consent record | The exact consent text and time are saved with each lead |
| Email alert | Sent to the site admin on every lead. Hook `npl_lead_saved` for a CRM, Zapier or Slack |
| Spam protection | Hidden honeypot field, signed timestamp (catches instant bot submits), per-visitor limit. Anything suspicious is saved as *Suspected spam* with no alert and no conversion event, so a real lead is never thrown away. **No nonce on purpose:** nonces expire on cached pages and silently lose real leads |
| Analytics events | Pushes to `dataLayer` for Google Tag Manager / GA4: `npl_form_start`, `npl_form_step`, `npl_form_error`, `generate_lead` (once per saved lead, checked on the server, so refreshes and copied URLs don't count), `npl_click_call`, `npl_click_cta`. Loads no Google scripts itself |
| Thank-you page | `noindex`, so search visits don't count as conversions |
| Privacy | Works with WordPress's *Export Personal Data* and *Erase Personal Data* tools. Consent wording covers calls and texts |
| Accessibility | Every field labelled. Errors are linked to their fields and listed in a summary. Focus moves to each new step, and the step number is announced. Choice steps say that picking an answer moves on. Works with keyboard only |

## Content migration

The old site's articles are in [`content/legacy-export/`](content/legacy-export/), with the kind of mess real exports have: page-builder shortcodes, inline styles, `<font>` tags, ALL CAPS titles, skipped heading levels, empty spacer paragraphs, a table without a header row, and hidden keyword text.

`migrate.php` keeps the words and structure and drops the styling. It then:

- fixes titles ("HAIL DAMAGE?? What To Do Next" → "What to do after a hail storm") and heading order
- rebuilds tables with a proper header row
- points old links to new pages (`/contact-us.html` → `/free-quote/`)
- removes hidden keyword text, and flags images that are missing or have no alt text instead of leaving them broken
- replaces old "CALL US TODAY!!!" buttons with the theme's quote box
- writes [`migration-report.md`](content/migration-report.md) (what changed, what a person should check) and [`redirects.csv`](content/redirects.csv) (old URL → new URL, for 301 redirects so no ranking is lost)

On a real project, every page still gets a human read-through after the script.

## How it's built

| Part | What it is |
|---|---|
| `theme/zoransmaleri` | Block theme: `theme.json`, HTML templates, PHP patterns. Two page templates: *Landing* (full-width sections) and *Focus* (no menu, for the funnel) |
| `plugins/zoransmaleri-leads` | Quote form block, leads inbox, export, tracking. A plugin, so leads survive a redesign |
| `plugins/zoransmaleri-demo-notes` | Demo bar and CRO notes only. **Delete on a real site.** Notes aren't stored in page content |
| `content/` | Demo setup script, migration script, old-site export |

**No other plugins.** No page builder, no jQuery on the front end, no form plugin licence to renew.

### How the team edits the site

| Task | Where |
|---|---|
| Change text or buttons on a page | Pages → open the page → click and type |
| Add a section | "+" → Patterns → **Zorans Måleri sections** (hero with form, trust strip, steps, services, price guide, reviews, comparison, promise, FAQ, final call to action) |
| Start a new landing page | Pages → Add new → pick a **Zorans Måleri page layout** → set template to *Landing page* |
| Add the quote form anywhere | Add the **Quote form** block, pick *Start* or *Full* |
| Change colours, fonts, buttons (whole site) | Appearance → Editor → Styles |
| Change the menu | Appearance → Editor → Navigation → Main menu |
| Change the answer choices | `plugins/zoransmaleri-leads/includes/fields.php` (one list feeds the form, inbox and export) |
| Work the leads | **Leads** in the admin menu |

## How it stays fast

- **One font file** (Figtree, 20 KB), stored on the site and preloaded. No Google Fonts call.
- **No photos.** Icons and the illustration are small SVG files (about 5 KB in total).
- **Form script: about 3 KB compressed, no libraries**, only on pages with a form. The site-wide script (lead source, click events, sticky bar) is about 1 KB compressed.
- **No layout shift** when the form turns into steps: a one-line inline script marks the page before it's drawn.
- FAQs use WordPress's own Details block: no JavaScript.
- WordPress's emoji script is removed.

## Test results

Tested locally on WordPress 6.6 (RC3, the newest build the test machine could get) with PHP 8.4. Playground runs the latest WordPress. Full details in [`proof/`](proof/README.md).

| Page | Speed | Accessibility | Best practices | SEO |
|---|---|---|---|---|
| Home | 96 | 100 | 100 | 100 |
| Roof replacement | 97 | 100 | 100 | 100 |
| Storm damage | 97 | 100 | 100 | 100 |
| Free quote | 100 | 100 | 100 | 100 |
| Thank you | 100 | 100 | 100 | 69 * |
| Guide article | 99 | 100 | 100 | 100 |

Lighthouse, mobile setting (simulated slow phone and network). Layout shift is 0 on every page.
\* The thank-you page is set to `noindex` on purpose, which Lighthouse scores as an SEO issue.

- **Accessibility scan (axe-core, WCAG 2.2 AA + best practices):** 0 issues on 14 URLs at desktop and mobile width, plus the form with errors showing.
- **Form tests (automated browser):** ZIP hand-over, auto-advance, keyboard only (Tab, arrows, Enter), back button, error messages and focus, campaign tracking from a blog post, conversion event fires once (not on refresh or a faked URL), no-JavaScript submit with server errors and kept answers, bot and honeypot submits saved as suspected spam with no event.
- **Admin:** leads list, detail view, status, CSV export, dashboard guide. An Author account can't open or export leads.
- **Editor:** all 12 pages and posts, and all 25 patterns, load with no invalid blocks.
- **Layout:** no sideways scrolling at 320, 375, 768, 820, 1024, 1280 and 1440px.
- **Blueprint:** passes the Playground schema, and the same setup was run on a fresh install.

Automated tools can't catch everything. A real project would add manual screen reader testing and live A/B tests.

## Run it locally

1. Copy `theme/zoransmaleri` into `wp-content/themes/` and activate it.
2. Copy both folders from `plugins/` into `wp-content/plugins/` and activate them.
3. Demo content: `wp eval-file content/setup.php` from the WordPress folder.

## Files

```
blueprint.json                Sets up the WordPress Playground demo
content/
  setup.php                   Pages, menu, settings, sample leads
  migrate.php                 Old HTML → clean blocks, report, redirects
  legacy-export/              Three articles as exported from the "old site"
  migration-report.md         Generated: what changed per article
  redirects.csv               Generated: old URL → new URL
theme/zoransmaleri/
  theme.json                  Colours, font, spacing, button styles
  style.css                   The few styles theme.json can't express
  functions.php               Font preload, meta description, business schema
  templates/                  Landing, focus, page, post, guides, 404
  parts/                      Header, footer (and funnel versions)
  patterns/                   Sections and full page layouts
  styles/sections/            Dark, sand and card styles
plugins/zoransmaleri-leads/
  blocks/lead-form/           Quote form block (no build step)
  includes/                   Fields, leads inbox, submit, export, tracking
  assets/track.js             Click events and sticky bar helper
plugins/zoransmaleri-demo-notes/ Demo bar and CRO notes (remove on a real site)
proof/                        Lighthouse reports, axe results, screenshots
```

## Licence

GPL-2.0-or-later, like WordPress. Font: SIL Open Font License (see `theme/zoransmaleri/assets/fonts/OFL.txt`).
