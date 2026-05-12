import { act } from 'react';
import { afterEach, describe, expect, it } from 'vitest';
import { mountReactIslands } from './boot';

(globalThis as typeof globalThis & { IS_REACT_ACT_ENVIRONMENT: boolean }).IS_REACT_ACT_ENVIRONMENT = true;

describe('mountReactIslands', () => {
    afterEach(() => {
        document.body.innerHTML = '';
    });

    it('mounts the home modules island from bootstrap JSON', () => {
        document.body.innerHTML = `
            <div id="home_module_list" data-react-root="home-modules" data-props-id="home-modules-props">
                <span>Fallback modules</span>
            </div>
            <script id="home-modules-props" type="application/json">
                {
                    "modules": [
                        {
                            "id": "sales",
                            "name": "Sales",
                            "description": "Process sales and returns",
                            "url": "http://localhost/ospos/sales",
                            "iconUrl": "http://localhost/ospos/images/menubar/sales.svg"
                        }
                    ]
                }
            </script>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="home-modules"]');
        const link = root?.querySelector('a[href="http://localhost/ospos/sales"]');

        expect(root?.textContent).toContain('Sales');
        expect(root?.textContent).toContain('Process sales and returns');
        expect(root?.textContent).not.toContain('Fallback modules');
        expect(link?.querySelector('img')?.getAttribute('src')).toBe('http://localhost/ospos/images/menubar/sales.svg');
    });

    it('mounts the shared shell navigation island', () => {
        document.body.innerHTML = `
            <div data-react-root="shell-navigation" data-props-id="shell-navigation-props">
                <span>Fallback nav</span>
            </div>
            <script id="shell-navigation-props" type="application/json">
                {
                    "company": "Open Source Point of Sale",
                    "currentTime": "05/12/2026 16:30:00",
                    "userName": "John Doe",
                    "changePasswordUrl": "http://localhost/ospos/home/changePassword/1",
                    "logoutUrl": "http://localhost/ospos/home/logout",
                    "homeUrl": "http://localhost/ospos/",
                    "modules": [
                        {
                            "id": "items",
                            "name": "Items",
                            "url": "http://localhost/ospos/items",
                            "iconUrl": "http://localhost/ospos/images/menubar/items.svg",
                            "active": true
                        }
                    ]
                }
            </script>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="shell-navigation"]');

        expect(root?.textContent).toContain('Open Source Point of Sale');
        expect(root?.textContent).toContain('John Doe');
        expect(root?.textContent).toContain('Items');
        expect(root?.textContent).not.toContain('Fallback nav');
        expect(root?.querySelector('a[href="http://localhost/ospos/items"]')?.className).toContain('is-active');
    });

    it('mounts the login page island and adopts the server-rendered form', () => {
        document.body.innerHTML = `
            <div data-react-root="login-page" data-props-id="login-page-props"></div>
            <script id="login-page-props" type="application/json">
                {
                    "company": "Open Source Point of Sale",
                    "product": "OSPOS",
                    "logoSelector": "#login-logo-source",
                    "formSelector": "#login-form-source",
                    "footerSelector": "#login-footer-source"
                }
            </script>
            <div id="login-logo-source"><svg class="logo"></svg></div>
            <section id="login-form-source"><form><input id="input-username" name="username"><button name="login-button">Go</button></form></section>
            <footer id="login-footer-source">Footer</footer>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="login-page"]');

        expect(root?.textContent).toContain('Open Source Point of Sale');
        expect(root?.querySelector('.op-login__form-host #input-username')).toBeTruthy();
        expect(root?.querySelector('.op-login__footer-host footer')?.textContent).toContain('Footer');
    });

    it('mounts the management page island and adopts legacy table nodes', () => {
        document.body.innerHTML = `
            <div id="title_bar"><button class="btn btn-info">New item</button></div>
            <div id="toolbar"><div><button id="delete" class="btn btn-default">Delete</button></div></div>
            <div id="table_holder"><table id="table"></table></div>
            <div data-react-root="management-page" data-props-id="management-page-props"></div>
            <script id="management-page-props" type="application/json">
                {
                    "resource": "items",
                    "title": "Items",
                    "description": "Manage stock and prices",
                    "uniqueId": "items.item_id",
                    "pageSize": 25,
                    "headers": [{ "field": "name" }]
                }
            </script>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="management-page"]');
        const workbench = root?.querySelector('.op-workbench');

        expect(workbench?.textContent).toContain('Items');
        expect(workbench?.textContent).toContain('Manage stock and prices');
        expect(workbench?.querySelector('.op-workbench__actions #title_bar .btn-info')?.textContent).toContain('New item');
        expect(workbench?.querySelector('.op-workbench__toolbar #toolbar #delete')?.textContent).toContain('Delete');
        expect(workbench?.querySelector('.op-workbench__table-frame #table_holder #table')).toBeTruthy();
        expect(document.body.classList.contains('ospos-management-react')).toBe(true);
    });

    it('mounts the tabbed shell island and adopts legacy tabs', () => {
        document.body.innerHTML = `
            <ul class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#general_tab">General</a></li>
                <li><a href="#tax_tab">Tax</a></li>
            </ul>
            <div class="tab-content">
                <div id="general_tab">General settings</div>
            </div>
            <div data-react-root="tabbed-shell" data-props-id="tabbed-shell-props"></div>
            <script id="tabbed-shell-props" type="application/json">
                {
                    "title": "Store configuration",
                    "description": "Manage setup",
                    "navSelector": ".nav-tabs[data-tabs=\\"tabs\\"]",
                    "contentSelector": ".tab-content"
                }
            </script>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="tabbed-shell"]');

        expect(root?.textContent).toContain('Store configuration');
        expect(root?.querySelector('.op-tabs__nav-host .nav-tabs')?.textContent).toContain('General');
        expect(root?.querySelector('.op-tabs__content-host .tab-content')?.textContent).toContain('General settings');
    });

    it('mounts the legacy auto shell and adopts otherwise unwrapped page content', () => {
        document.body.innerHTML = `
            <main class="row">
                <div data-react-root="legacy-auto-shell" data-props-id="legacy-auto-shell-props"></div>
                <script id="legacy-auto-shell-props" type="application/json">
                    {
                        "title": "Reports",
                        "section": "Listing"
                    }
                </script>
                <div id="page_title">Report input</div>
                <form id="item_form"><input class="form-control" name="daterange"><button class="btn btn-primary">Submit</button></form>
            </main>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const root = document.querySelector('[data-react-root="legacy-auto-shell"]');

        expect(root?.textContent).toContain('Report input');
        expect(root?.textContent).toContain('Listing');
        expect(root?.querySelector('.op-page-shell__content #item_form .op-page-shell__control')).toBeTruthy();
        expect(root?.querySelector('.op-page-shell__content #item_form .op-page-shell__button')).toBeTruthy();
    });

    it('leaves legacy auto shell hidden when a dedicated React page island exists', () => {
        document.body.innerHTML = `
            <main class="row">
                <div data-react-root="legacy-auto-shell" data-props-id="legacy-auto-shell-props"></div>
                <script id="legacy-auto-shell-props" type="application/json">
                    {
                        "title": "Items"
                    }
                </script>
                <div data-react-root="management-page" data-props-id="management-page-props"></div>
                <script id="management-page-props" type="application/json">
                    {
                        "resource": "items",
                        "title": "Items"
                    }
                </script>
            </main>
        `;

        act(() => {
            mountReactIslands(document);
        });

        const autoShell = document.querySelector<HTMLElement>('[data-react-root="legacy-auto-shell"] .op-page-shell');
        const management = document.querySelector('[data-react-root="management-page"] .op-workbench');

        expect(autoShell?.hidden).toBe(true);
        expect(management?.textContent).toContain('Items');
    });
});
