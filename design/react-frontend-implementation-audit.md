# React Frontend Implementation Audit

## Objective

Make a complete fully responsive frontend in React with shadcn components for OSPOS in a new git branch.

## Success Criteria

- The work lives on a dedicated branch: `codex/react-ui-refactor`.
- The authenticated app shell is rendered by React and uses shadcn-compatible components/styles.
- The public login screen is rendered by React while preserving the server-rendered form behavior.
- Home and office module launchers are rendered by React.
- Management list pages use React/shadcn framing while preserving Bootstrap Table behavior.
- Config and tax tabbed pages use React/shadcn framing while preserving legacy tab content.
- Remaining header-rendered legacy pages are adopted into a responsive React/shadcn page shell.
- AJAX Bootstrap modal/form fragments are enhanced by a React-managed observer for responsive modal framing.
- Desktop and mobile smoke checks confirm no page-level horizontal overflow on representative core routes.
- Existing PHP and frontend build/test commands pass.

## Assumptions

- "Complete frontend in React" is implemented as React-owned page chrome, navigation, responsive framing, and shadcn-based surfaces across browser-visible pages.
- Legacy PHP workflow internals, POS register logic, forms, receipts, Bootstrap tables, modal behaviors, and server-rendered data stay intact and are adopted into React shells rather than rewritten in this branch.
- A literal native React rewrite of every workflow is a larger product migration and is outside this branch's safe scope.

## Productive Thinking Model

1. What is going on?
   The current app is PHP-rendered with Bootstrap-era layouts and several wide workflow surfaces.
2. What is success?
   React visibly owns the frontend shell and responsive page framing without breaking OSPOS behavior.
3. What is the question?
   How can React/shadcn cover the full UI surface while keeping risk low?
4. Generate answers.
   Use targeted React islands for high-value surfaces and a shared adoption shell for remaining legacy pages.
5. Choose the solution.
   Use a hybrid island architecture: specific islands where structure is known, auto shell where content varies.
6. Align resources.
   Add Vite, React, shadcn components, tests, and browser smoke checks.

## Iceberg Model

- Event: OSPOS pages look inconsistent and are not reliably responsive.
- Pattern: Many views share the same header/footer but differ in inner markup.
- Structure: UI behavior is distributed across PHP views, jQuery plugins, Bootstrap Table, and page-specific scripts.
- Mental model: Rewriting every workflow at once would be cleaner conceptually but too risky for a POS system.
- Root cause: The frontend lacks a unified responsive shell boundary that can modernize the page while preserving existing domain workflows.

## Decision Matrix

| Option | Impact | Effort | Risk | Decision |
| --- | --- | --- | --- | --- |
| Full native React rewrite of every view | High | Very high | Very high | Defer |
| React islands only on a few key pages | Medium | Medium | Low | Insufficient coverage |
| React/shadcn shell plus legacy DOM adoption | High | Medium | Medium | Selected |
| CSS-only Bootstrap polish | Medium | Low | Low | Not enough React/shadcn |

## Zwicky Box

| Dimension | Choice |
| --- | --- |
| Rendering strategy | React islands mounted into PHP views |
| Styling system | Tailwind v4 plus shadcn-compatible components |
| Legacy behavior | Adopt existing DOM nodes into React hosts |
| Responsiveness | Mobile-first card/shell framing with local horizontal scroll for data-heavy content |
| Verification | Typecheck, Vitest, Vite build, PHPUnit command, Playwright smoke |

## Coverage Checklist

| Requirement | Evidence |
| --- | --- |
| New branch | `git branch --show-current` returns `codex/react-ui-refactor` |
| React stack | `package.json`, `vite.config.ts`, `tsconfig.json`, `src/react/main.tsx` |
| shadcn components | `components.json`, `src/react/components/ui/*`, shadcn imports in React components |
| Auth shell | `app/Views/partial/header.php`, `src/react/shell/ShellNavigation.tsx` |
| Login | `app/Views/login.php`, `src/react/login/LoginPage.tsx` |
| Home/office | `app/Views/home/*.php`, `src/react/home/HomeModules.tsx` |
| Management pages | `app/Views/partial/management_shell.php`, `src/react/management/ManagementPage.tsx` |
| Tabbed config/tax pages | `app/Views/partial/tabbed_shell.php`, `src/react/tabs/TabbedShell.tsx` |
| Remaining legacy pages | `src/react/legacy/LegacyAutoShell.tsx` mounted from `partial/header.php` |
| Modal/form fragments | `src/react/legacy/LegacyModalObserver.tsx` mounted from `partial/header.php` |
| Unit coverage | `src/react/boot.test.tsx` covers all React root types |
| Build and runtime | `npm run build`, `npm run typecheck`, `npm run test:ui`, `composer test`, Playwright smoke |

## Known Limit

This branch does not replace every PHP view, jQuery plugin, report table, receipt, and register workflow with native React state/components. It gives every shared-header browser page and Bootstrap modal/form fragment a React-managed responsive surface while retaining existing server-rendered workflow internals.
