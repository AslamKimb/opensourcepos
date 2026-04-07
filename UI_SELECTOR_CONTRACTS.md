# UI Selector Contracts (Do-Not-Rename)

This note inventories JavaScript selector dependencies that are used as integration hooks between `public/js/*` scripts and `app/Views/**` templates.

## Why this exists

Several UI scripts bind to hard-coded IDs, classes, and `data-*` attributes. Renaming those hooks in views without compatibility aliases can silently break modals, table actions, register flows, or report toggles.

When updating templates, keep these selectors stable. If wrapper changes are required, **add compatibility classes/attributes** instead of replacing existing hooks.

## Inventory: selector dependencies by script

### `public/js/manage_tables.js`

**Dialog/modal hooks**
- `a.modal-dlg`, `button.modal-dlg` (modal launcher binding)
- `.modal-dlg` and `.modal-dlg-*` (dialog width class detection)
- `data-btn-*` button labels (`data-btn-submit`, `data-btn-delete`, `data-btn-new`, etc.)
- `data-href` (alternate endpoint for modal payload)
- `#submit` (disable submit button after validation)

**Bootstrap table/action hooks**
- `#table` (bootstrap table root + row selection)
- `#toolbar` (table toolbar binding)
- `#delete`, `#restore` (bulk action buttons)
- `tr[data-uniqueid='...']` (row update/highlight/delete)

**Form validation hooks**
- `#error_message_box` (jQuery validate error label container)
- `.form-group` + `.has-error` (error highlight/unhighlight)

### `public/js/hide_cost_profit.js`

- `#toggleCostProfitButton` (toggle action)
- `#chart_report_summary .summary_row` (target rows; script expects the final two rows to be cost/profit)

### `public/js/nominatim.autocomplete.js`

- `#<field_id>` selectors from `nominatim.init({ fields: ... })`
- `dependencies` list in `nominatim.init` must match real form input IDs
- `.modal-content` (`appendTo` target for autocomplete menu inside modals)

## Do-not-rename list

Avoid renaming/removing these hooks unless you also keep backward-compatible aliases:

- `#table`
- `#toolbar`
- `#delete`
- `#restore`
- `.modal-dlg`
- `.modal-dlg-*` (e.g. width variants)
- `.modal-content`
- `#submit` (for modal forms)
- `#error_message_box`
- `.form-group`
- `.has-error`
- `#toggleCostProfitButton`
- `#chart_report_summary`
- `.summary_row`
- Button `data-btn-*` attributes (`data-btn-submit`, `data-btn-delete`, `data-btn-new`, ...)
- Modal link/button `data-href`
- Nominatim/autocomplete form field IDs referenced in `dependencies`
- Register containers in sales/receivings views: `#register_wrapper`, `#register`

## View-to-contract checklist (lightweight)

Use this checklist during `app/Views/**` updates.

| View file(s) | Required selector contract |
| --- | --- |
| `app/Views/*/manage.php`, `app/Views/reports/tabular*.php`, `app/Views/reports/tabular_details.php`, `app/Views/taxes/tax_rates.php` | Keep `#table` + `#toolbar`; keep bulk action IDs like `#delete`/`#restore` when present. |
| Views with modal triggers (e.g., `app/Views/items/manage.php`, `app/Views/people/manage.php`, `app/Views/sales/register.php`, `app/Views/receivings/receiving.php`, header/account links) | Keep `.modal-dlg` and `data-btn-*`/`data-href` hooks on launch controls. |
| Form views using jQuery validation (e.g., `app/Views/*/form.php`) | Keep `#error_message_box`; keep `.form-group` wrappers around validated fields. |
| `app/Views/reports/graphical.php`, `app/Views/reports/tabular.php`, `app/Views/reports/tabular_details.php` | Keep `#toggleCostProfitButton`; keep `#chart_report_summary .summary_row` structure for graphical summary rows. |
| `app/Views/people/form_basic_info.php` | Keep the field IDs listed in `nominatim.init(...dependencies...)` (`address_1`, `city`, `state`, `postcode`, `country`, etc.) stable. |
| `app/Views/sales/register.php`, `app/Views/receivings/receiving.php` | Keep register container IDs `#register_wrapper` and `#register` stable for register UI scripts/styles. |

## Template refactor rule

If you must change wrappers/markup:
1. Prefer additive changes (add new wrappers/classes).
2. Preserve existing hook selectors on the same or equivalent elements.
3. If moving elements, keep the old selector as a compatibility class/ID/data attribute.
4. Verify affected flows (manage tables, modal forms, register, reports) before merge.
