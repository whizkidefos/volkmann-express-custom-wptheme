# Volkmann Express — WordPress Theme

**Version:** 1.0.0 | **Built by:** Yohanorg Limited | **Stack:** PHP 8.1+, Tailwind CSS (CDN), GSAP 3.12, THREE.js r128, Chart.js 4, Inter font

---

## Quick Start

1. Upload `volkmann-express/` to `wp-content/themes/`
2. Activate in *Appearance → Themes*
3. Flush permalinks: *Settings → Permalinks → Save Changes*
4. Create pages and assign the correct Template (see table below)
5. Set menus: *Appearance → Menus* — create **Primary** and **Footer** menus
6. Configure: *Appearance → Customise → Volkmann Express Settings*

---

## Page Templates

| Page             | Template Name     | Recommended slug |
|------------------|-------------------|-----------------|
| Home             | (set as Front Page) | `/`           |
| About            | About             | `about`         |
| Solutions Hub    | Solutions Hub     | `solutions`     |
| Industries Hub   | Industries Hub    | `industries`    |
| Contact          | Contact           | `contact`       |
| Privacy Policy   | Privacy Policy    | `privacy`       |
| Terms of Service | Terms of Service  | `terms`         |
| FAQ              | FAQ               | `faq`           |
| Careers          | Careers           | `careers`       |

---

## Custom Post Types

**Service** (`/solutions/{slug}`) — Meta fields: `ve_hero_headline`, `ve_hero_sub`, `ve_cta_label`, `ve_cta_url`, `ve_result_1_value`/`_label` (×3), `ve_proof_title`, `ve_proof_story`

**Industry** (`/industries/{slug}`) — Meta fields: `ve_solutions` (comma-separated), `ve_stat_1_value`/`_label` (×3)

**Case Study** (`/case-studies/{slug}`) — Meta fields: `ve_client`, `ve_industry`, `ve_challenge`, `ve_solution`, `ve_result`, `ve_results` (JSON), `ve_testimonial`, `ve_quote_author`

**Team Member** — Meta fields: `ve_role`, `ve_linkedin`, `ve_email`

If **ACF** is active, native meta boxes are skipped automatically. Create ACF field groups using the same `ve_*` field names.

---

## Customiser Settings

*Appearance → Customise → Volkmann Express Settings*: `ve_phone`, `ve_email`, `ve_address`, `ve_hours`, `ve_linkedin`, `ve_twitter`

---

## Shortcodes

```
[ve_cta label="Schedule a Consultation" url="/contact" style="primary"]
[ve_badge text="Enterprise Technology"]
[ve_stat value="200+" label="Enterprise Clients"]
[ve_services count="6"]
[ve_industries count="12"]
[ve_metrics metrics="200+ Clients, 98% Retention, 12+ Industries"]
[ve_contact_form]
```

---

## Dark / Light Mode

Defaults to dark. Toggle persists in `localStorage` under key `ve_theme`. System preference (`prefers-color-scheme`) respected on first visit.

---

## AJAX Contact Form

Submits to `admin-ajax.php` action `ve_contact`. Leads stored in `wp_options` as `ve_leads`. Viewable at **VE Leads** in the admin menu. Email notification sent to `admin_email`. To route leads to a CRM, add a webhook inside `ve_handle_contact()` in `functions.php`.

---

## SEO Module

`inc/seo.php` auto-disables if Yoast, RankMath, or AIOSEO is active. Outputs: meta description, canonical, Open Graph, Twitter Card, and Schema.org JSON-LD (Organization, WebSite with SearchAction, BreadcrumbList, Service, Article).

---

## File Structure

```
volkmann-express/
├── style.css / functions.php / front-page.php / header.php / footer.php
├── index.php / single.php / page.php / search.php / 404.php
├── page-{about,solutions,industries,contact,privacy,terms,faq,careers}.php
├── single-{service,industry,case_study}.php
├── archive-{service,industry,case_study}.php
├── template-parts/  hero · cta · contact-form · metrics · cards-service · cards-industry
├── inc/             helpers · nav-walker · meta-boxes · seo · shortcodes · widgets
├── assets/css/      main.css (~1960 lines) · admin.css
├── assets/js/       main.js (~920 lines) · admin.js
├── assets/images/   logo.png
└── languages/       (translation-ready)
```

---

## Build Phases

| Phase | Scope | Status |
|-------|-------|--------|
| 1 | Scaffold, header, footer, home, page.php | ✅ |
| 2 | Service + Industry CPTs and templates | ✅ |
| 3 | Contact, legal pages, footer nav | ✅ |
| 4 | Case Studies, Insights, SEO, analytics setup | ✅ |
| 5 | QA, performance, accessibility, launch redirects | 🔲 Client |

*Built by Yohanorg Limited — yohanorg.com*
