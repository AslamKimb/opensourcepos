<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UiSelectorContractTest extends TestCase
{
    public function testManageTablesScriptKeepsCoreSelectors(): void
    {
        $source = file_get_contents(__DIR__ . '/../../public/js/manage_tables.js');

        $this->assertNotFalse($source);
        $this->assertStringContainsString('#table', $source);
        $this->assertStringContainsString('#toolbar', $source);
        $this->assertStringContainsString('a.modal-dlg', $source);
        $this->assertStringContainsString('button.modal-dlg', $source);
        $this->assertStringContainsString('#delete', $source);
        $this->assertStringContainsString('#restore', $source);
        $this->assertStringContainsString('#error_message_box', $source);
        $this->assertStringContainsString("'.form-group'", $source);
    }

    public function testCostProfitToggleScriptKeepsSummarySelectors(): void
    {
        $source = file_get_contents(__DIR__ . '/../../public/js/hide_cost_profit.js');

        $this->assertNotFalse($source);
        $this->assertStringContainsString('#toggleCostProfitButton', $source);
        $this->assertStringContainsString('#chart_report_summary .summary_row', $source);
    }

    public function testNominatimScriptKeepsModalAndDependencyHooks(): void
    {
        $source = file_get_contents(__DIR__ . '/../../public/js/nominatim.autocomplete.js');

        $this->assertNotFalse($source);
        $this->assertStringContainsString('dependencies', $source);
        $this->assertStringContainsString('$("#" + key).autocomplete', $source);
        $this->assertStringContainsString("appendTo: '.modal-content'", $source);
    }

    public function testRepresentativeViewsExposeManageTableContracts(): void
    {
        $viewFiles = [
            __DIR__ . '/../../app/Views/items/manage.php',
            __DIR__ . '/../../app/Views/sales/manage.php',
            __DIR__ . '/../../app/Views/reports/tabular.php',
        ];

        foreach ($viewFiles as $viewFile) {
            $source = file_get_contents($viewFile);
            $this->assertNotFalse($source);
            $this->assertStringContainsString('id="toolbar"', $source, $viewFile);
            $this->assertStringContainsString('id="table"', $source, $viewFile);
        }
    }

    public function testRepresentativeViewsExposeModalAndAutocompleteContracts(): void
    {
        $salesRegister = file_get_contents(__DIR__ . '/../../app/Views/sales/register.php');
        $peopleForm    = file_get_contents(__DIR__ . '/../../app/Views/people/form_basic_info.php');

        $this->assertNotFalse($salesRegister);
        $this->assertNotFalse($peopleForm);

        $this->assertStringContainsString('modal-dlg', $salesRegister);
        $this->assertStringContainsString('data-btn-submit', $salesRegister);
        $this->assertStringContainsString('id="register_wrapper"', $salesRegister);
        $this->assertStringContainsString('id="register"', $salesRegister);

        $this->assertStringContainsString('nominatim.init', $peopleForm);
        $this->assertStringContainsString('dependencies', $peopleForm);
        $this->assertStringContainsString('"postcode"', $peopleForm);
        $this->assertStringContainsString('"city"', $peopleForm);
        $this->assertStringContainsString('"state"', $peopleForm);
        $this->assertStringContainsString('"country"', $peopleForm);
    }

    public function testSalesRegisterTouchOptimizedLayoutContractsExist(): void
    {
        $salesRegister = file_get_contents(__DIR__ . '/../../app/Views/sales/register.php');
        $registerCss   = file_get_contents(__DIR__ . '/../../public/css/register.css');

        $this->assertNotFalse($salesRegister);
        $this->assertNotFalse($registerCss);

        foreach ([
            'id="register_wrapper"',
            'id="register"',
            'id="cart_contents"',
            'id="overall_sale"',
            'id="payment_details"',
            "'id' => 'amount_tendered'",
            'id="add_payment_button"',
            'id="finish_sale_button"',
            'id="suspend_sale_button"',
            'id="cancel_sale_button"',
            'modal-dlg',
            'data-btn-submit',
        ] as $selectorContract) {
            $this->assertStringContainsString($selectorContract, $salesRegister);
        }

        foreach ([
            'register-touch-shell',
            'register-workspace',
            'register-control-strip',
            'register-scan-bar',
            'register-cart-workspace',
            'register-cart-table',
            'register-cart-row',
            'register-mobile-item-card',
            'register-checkout-rail',
            'register-sticky-totals',
            'register-payment-action-zone',
            'data-label=',
        ] as $layoutContract) {
            $this->assertStringContainsString($layoutContract, $salesRegister);
        }

        foreach ([
            '.register-touch-shell',
            '.register-workspace',
            '.register-control-strip',
            '#mode_form > .panel-body > ul',
            '.register-control-strip .bootstrap-select',
            '.register-control-strip .dropdown-menu',
            '.register-scan-bar',
            '.register-cart-workspace',
            '.register-cart-table',
            '.register-checkout-rail',
            '.register-sticky-totals',
            '.register-payment-action-zone',
            '.register-mobile-item-card',
            '#cart_contents .register-mobile-item-card',
            'content: attr(data-label)',
            'position: sticky',
            '@media (max-width: 767px)',
        ] as $cssContract) {
            $this->assertStringContainsString($cssContract, $registerCss);
        }
    }

    public function testModernUiRefreshKeepsSharedPageFamilyHooks(): void
    {
        $header      = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $home        = file_get_contents(__DIR__ . '/../../app/Views/home/home.php');
        $office      = file_get_contents(__DIR__ . '/../../app/Views/home/office.php');
        $themeCss    = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss      = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
        $registerCss = file_get_contents(__DIR__ . '/../../public/css/register.css');
        $reportsCss  = file_get_contents(__DIR__ . '/../../public/css/reports.css');
        $invoiceCss  = file_get_contents(__DIR__ . '/../../public/css/invoice.css');
        $receiptCss  = file_get_contents(__DIR__ . '/../../public/css/receipt.css');

        $this->assertNotFalse($header);
        $this->assertNotFalse($home);
        $this->assertNotFalse($office);
        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($appCss);
        $this->assertNotFalse($registerCss);
        $this->assertNotFalse($reportsCss);
        $this->assertNotFalse($invoiceCss);
        $this->assertNotFalse($receiptCss);

        $this->assertStringContainsString('class="ospos-app"', $header);
        $this->assertStringContainsString('class="wrapper app-shell"', $header);
        $this->assertStringContainsString('app-navbar', $header);
        $this->assertStringContainsString('class="app-shellbar mobile-shellbar app-navbar"', $header);
        $this->assertStringContainsString('class="app-layout"', $header);
        $this->assertStringContainsString('class="navbar navbar-default app-navbar app-sidebar"', $header);
        $this->assertStringContainsString('id="app-navigation"', $header);
        $this->assertStringContainsString('class="topbar app-commandbar"', $header);
        $this->assertStringContainsString('aria-current="page"', $header);
        $this->assertStringContainsString('module-grid', $home);
        $this->assertStringContainsString('module-grid', $office);

        $this->assertStringContainsString('--ui-bg:', $themeCss);
        $this->assertStringContainsString('--ui-surface:', $themeCss);
        $this->assertStringContainsString('--ui-accent:', $themeCss);
        $this->assertStringContainsString('.fixed-table-container', $themeCss);
        $this->assertStringContainsString('.bootstrap-dialog', $themeCss);
        $this->assertStringContainsString('.nav-tabs', $themeCss);

        $this->assertStringContainsString('.module-grid', $appCss);
        $this->assertStringContainsString('.app-layout', $appCss);
        $this->assertStringContainsString('.app-sidebar', $appCss);
        $this->assertStringContainsString('.mobile-shellbar', $appCss);
        $this->assertStringContainsString('.app-sidebar-collapse.collapse', $appCss);
        $this->assertStringContainsString('@media (max-width: 991px)', $appCss);
        $this->assertStringContainsString('#table_holder', $appCss);
        $this->assertStringContainsString('.utility-message', $appCss);

        $this->assertStringContainsString('.register-layout', $registerCss);
        $this->assertStringContainsString('.transaction-panel', $registerCss);
        $this->assertStringContainsString('.sales_table_100', $registerCss);

        $this->assertStringContainsString('.report-card', $reportsCss);
        $this->assertStringContainsString('#report_summary', $reportsCss);

        $this->assertStringContainsString('#page-wrap', $invoiceCss);
        $this->assertStringContainsString('#receipt_wrapper', $receiptCss);
    }

    public function testUnifiedShellSupportsCrossScreenSidebarToggle(): void
    {
        $header   = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $headerJs = file_get_contents(__DIR__ . '/../../app/Views/partial/header_js.php');
        $appCss   = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertNotFalse($header);
        $this->assertNotFalse($headerJs);
        $this->assertNotFalse($appCss);

        foreach ([
            'class="app-shellbar mobile-shellbar app-navbar"',
            'class="navbar-toggle app-menu-toggle app-shell-toggle"',
            'app-shell-primary',
            'class="topbar-item topbar-company app-shell-context"',
            'class="navbar-right topbar-item topbar-actions app-shell-actions"',
            'class="app-shell-account"',
            'class="app-shell-backdrop"',
            'data-target="#app-navigation"',
            'id="app-navigation"',
        ] as $headerContract) {
            $this->assertStringContainsString($headerContract, $header);
        }

        $this->assertStringNotContainsString('class="app-brand-panel"', $header);

        foreach ([
            'ospos.shell.collapsed',
            'app-shell-collapsed',
            'app-shell-nav-open',
            'app-shell-toggle',
            'app-shell-backdrop',
            'matchMedia',
            'localStorage',
            '#app-navigation a',
            'Escape',
        ] as $jsContract) {
            $this->assertStringContainsString($jsContract, $headerJs);
        }

        foreach ([
            '.app-shellbar {',
            '.app-shell-primary {',
            '.app-shell-actions {',
            '.app-shell-backdrop {',
            '.app-shell.app-shell-collapsed .app-layout',
            '.app-shell.app-shell-collapsed .app-sidebar {',
            '.app-shell.app-shell-collapsed .app-sidebar-collapse',
            '.app-shell.app-shell-nav-open .app-sidebar',
            '.app-shell.app-shell-nav-open .app-shell-backdrop',
            '.app-shell-toggle',
            '@media (max-width: 991px)',
        ] as $cssContract) {
            $this->assertStringContainsString($cssContract, $appCss);
        }
    }

    public function testSharedInteractionStateContractsExist(): void
    {
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss   = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($appCss);

        foreach ([
            '--ui-state-hover-bg:',
            '--ui-state-active-bg:',
            '--ui-state-selected-bg:',
            '--ui-state-disabled-bg:',
            '--ui-state-invalid-bg:',
            '--ui-state-valid-bg:',
            '--ui-focus-ring-strong:',
        ] as $stateToken) {
            $this->assertStringContainsString($stateToken, $themeCss);
        }

        foreach ([
            ':focus-visible',
            '.btn:active',
            '.btn.active',
            '.btn.disabled',
            '.btn[disabled]',
            '.form-control:hover',
            '.form-control[disabled]',
            '.form-control.error',
            'label.error',
            '.alert-dismissible .close',
            '.nav-tabs > li > a:hover',
            '.nav-tabs > li.disabled > a',
            '.dropdown-menu > .active > a',
            '.dropdown-menu > .disabled > a',
            '.bootstrap-select .dropdown-menu > li.selected > a',
            '.fixed-table-container tbody tr.selected',
            '.table > tbody > tr.active > td',
            '.pagination > .disabled > a',
            '.modal-header .close:active',
            '.fileinput .thumbnail',
            '.btn-file:focus-within',
            '.bootstrap-tagsinput.focus',
            'input[type="checkbox"]:focus-visible',
            'input[type="radio"]:focus-visible',
        ] as $interactionContract) {
            $this->assertStringContainsString($interactionContract, $themeCss);
        }

        foreach ([
            '.config-upload .btn-file:focus-within',
            '.manage-table-surface .fixed-table-toolbar .search .form-control:hover',
            '.manage-table-grid > tbody > tr.selected > td',
            '.config-page .form-group.has-error',
        ] as $appStateContract) {
            $this->assertStringContainsString($appStateContract, $appCss);
        }
    }

    public function testDesignTokenLayerContractsExist(): void
    {
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $brandCss = file_get_contents(__DIR__ . '/../../app/Views/partial/brand_css.php');

        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($brandCss);

        foreach ([
            '--ui-color-canvas:',
            '--ui-color-canvas-subtle:',
            '--ui-color-surface:',
            '--ui-color-surface-muted:',
            '--ui-color-surface-strong:',
            '--ui-color-text:',
            '--ui-color-text-muted:',
            '--ui-color-text-soft:',
            '--ui-color-brand:',
            '--ui-color-brand-strong:',
            '--ui-color-brand-soft:',
            '--ui-color-action:',
            '--ui-color-action-strong:',
            '--ui-color-border:',
            '--ui-color-border-strong:',
            '--ui-color-success:',
            '--ui-color-success-bg:',
            '--ui-color-warning:',
            '--ui-color-warning-bg:',
            '--ui-color-danger:',
            '--ui-color-danger-bg:',
            '--ui-color-info:',
            '--ui-color-info-bg:',
            '--ui-space-7:',
            '--ui-space-8:',
            '--ui-radius-control:',
            '--ui-radius-surface:',
            '--ui-radius-pill:',
            '--ui-shadow-none:',
            '--ui-shadow-sm:',
            '--ui-shadow-md:',
            '--ui-shadow-lg:',
            '--ui-font-size-3xl:',
            '--ui-font-weight-normal:',
            '--ui-font-weight-medium:',
            '--ui-font-weight-semibold:',
            '--ui-font-weight-bold:',
            '--ui-font-weight-extrabold:',
            '--ui-control-height-sm:',
            '--ui-control-height-md:',
            '--ui-control-height-lg:',
            '--ui-table-density-compact-y:',
            '--ui-table-density-default-y:',
            '--ui-table-density-comfortable-y:',
            '--ui-breakpoint-xs:',
            '--ui-breakpoint-sm:',
            '--ui-breakpoint-md:',
            '--ui-breakpoint-lg:',
            '--ui-breakpoint-xl:',
            '--ui-breakpoint-xxl:',
            '--ui-breakpoint-mobile-max:',
            '--ui-breakpoint-shell-collapse:',
        ] as $designToken) {
            $this->assertStringContainsString($designToken, $themeCss);
        }

        foreach ([
            '--ui-bg: var(--ui-color-canvas);',
            '--ui-bg-subtle: var(--ui-color-canvas-subtle);',
            '--ui-surface: var(--ui-color-surface);',
            '--ui-text: var(--ui-color-text);',
            '--ui-accent: var(--ui-color-brand);',
            '--ui-action: var(--ui-color-action);',
            '--ui-danger: var(--ui-color-danger);',
            '--ui-elevation-1: var(--ui-shadow-sm);',
            '--ui-control-height: var(--ui-control-height-md);',
            '--ui-table-cell-padding-y: var(--ui-table-density-default-y);',
            '--ui-status-success-bg: var(--ui-color-success-bg);',
            '--bs-body-bg: var(--ui-bg);',
            '--bs-primary: var(--ui-action);',
        ] as $aliasContract) {
            $this->assertStringContainsString($aliasContract, $themeCss);
        }

        foreach ([
            '--ui-color-canvas: var(--ui-bg);',
            '--ui-color-surface: var(--ui-surface);',
            '--ui-color-text: var(--ui-text);',
            '--ui-color-brand: var(--ui-accent);',
            '--ui-color-action: var(--ui-action);',
            '--ui-color-success: var(--ui-success);',
            '--ui-color-warning: var(--ui-warning);',
            '--ui-color-danger: var(--ui-danger);',
            '--ui-color-info: var(--ui-info);',
        ] as $brandBridgeContract) {
            $this->assertStringContainsString($brandBridgeContract, $brandCss);
        }
    }

    public function testRuntimeBrandingViewsDoNotHardCodeProductBrand(): void
    {
        $header          = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $login           = file_get_contents(__DIR__ . '/../../app/Views/login.php');
        $footer          = file_get_contents(__DIR__ . '/../../app/Views/partial/footer.php');
        $home            = file_get_contents(__DIR__ . '/../../app/Views/home/home.php');
        $office          = file_get_contents(__DIR__ . '/../../app/Views/home/office.php');
        $invoiceEmail    = file_get_contents(__DIR__ . '/../../app/Views/sales/invoice_email.php');
        $quoteEmail      = file_get_contents(__DIR__ . '/../../app/Views/sales/quote_email.php');
        $workOrderEmail  = file_get_contents(__DIR__ . '/../../app/Views/sales/work_order_email.php');
        $invoiceCss      = file_get_contents(__DIR__ . '/../../public/css/invoice.css');
        $invoiceEmailCss = file_get_contents(__DIR__ . '/../../public/css/invoice_email.css');

        $this->assertNotFalse($header);
        $this->assertNotFalse($login);
        $this->assertNotFalse($footer);
        $this->assertNotFalse($home);
        $this->assertNotFalse($office);
        $this->assertNotFalse($invoiceEmail);
        $this->assertNotFalse($quoteEmail);
        $this->assertNotFalse($workOrderEmail);
        $this->assertNotFalse($invoiceCss);
        $this->assertNotFalse($invoiceEmailCss);

        $this->assertStringContainsString('brand_short_name($config)', $header);
        $this->assertStringContainsString('brand_favicon_href($config)', $header);
        $this->assertStringContainsString("view('partial/brand_css'", $header);
        $this->assertStringNotContainsString('>OSPOS</a>', $header);
        $this->assertStringNotContainsString("Powered by') . ' OSPOS", $header);

        $this->assertStringContainsString('brand_short_name($config)', $login);
        $this->assertStringContainsString('brand_favicon_href($config)', $login);
        $this->assertStringContainsString("view('partial/brand_css'", $login);
        $this->assertStringNotContainsString("lang('Common.software_short')", $login);
        $this->assertStringNotContainsString("lang('Common.software_title')", $login);

        $this->assertStringContainsString('brand_show_powered_by($config)', $footer);
        $this->assertStringContainsString('brand_short_name($config)', $home);
        $this->assertStringContainsString('brand_short_name($config)', $office);
        $this->assertStringContainsString("view('partial/brand_css'", $invoiceEmail);
        $this->assertStringContainsString("view('partial/brand_css'", $quoteEmail);
        $this->assertStringContainsString("view('partial/brand_css'", $workOrderEmail);
        $this->assertStringContainsString('background: var(--ui-accent)', $invoiceCss);
        $this->assertStringContainsString('background-color: var(--ui-accent', $invoiceEmailCss);
    }

    public function testSalesDocumentsUseModernDocumentContracts(): void
    {
        $invoice         = file_get_contents(__DIR__ . '/../../app/Views/sales/invoice.php');
        $taxInvoice      = file_get_contents(__DIR__ . '/../../app/Views/sales/tax_invoice.php');
        $quote           = file_get_contents(__DIR__ . '/../../app/Views/sales/quote.php');
        $workOrder       = file_get_contents(__DIR__ . '/../../app/Views/sales/work_order.php');
        $invoiceEmail    = file_get_contents(__DIR__ . '/../../app/Views/sales/invoice_email.php');
        $quoteEmail      = file_get_contents(__DIR__ . '/../../app/Views/sales/quote_email.php');
        $workOrderEmail  = file_get_contents(__DIR__ . '/../../app/Views/sales/work_order_email.php');
        $receiptDefault  = file_get_contents(__DIR__ . '/../../app/Views/sales/receipt_default.php');
        $receiptShort    = file_get_contents(__DIR__ . '/../../app/Views/sales/receipt_short.php');
        $receiptEmail    = file_get_contents(__DIR__ . '/../../app/Views/sales/receipt_email.php');
        $invoiceCss      = file_get_contents(__DIR__ . '/../../public/css/invoice.css');
        $invoiceEmailCss = file_get_contents(__DIR__ . '/../../public/css/invoice_email.css');
        $receiptCss      = file_get_contents(__DIR__ . '/../../public/css/receipt.css');

        foreach ([
            $invoice,
            $taxInvoice,
            $quote,
            $workOrder,
            $invoiceEmail,
            $quoteEmail,
            $workOrderEmail,
            $receiptDefault,
            $receiptShort,
            $receiptEmail,
            $invoiceCss,
            $invoiceEmailCss,
            $receiptCss,
        ] as $source) {
            $this->assertNotFalse($source);
        }

        foreach ([$invoice, $taxInvoice, $quote, $workOrder, $invoiceEmail, $quoteEmail, $workOrderEmail] as $documentView) {
            $this->assertStringContainsString('class="document-shell', $documentView);
            $this->assertStringContainsString('class="document-header"', $documentView);
            $this->assertStringContainsString('document-status-badge', $documentView);
        }

        foreach ([$receiptDefault, $receiptShort, $receiptEmail] as $receiptView) {
            $this->assertStringContainsString('class="receipt-shell"', $receiptView);
            $this->assertStringContainsString('receipt-status-badge', $receiptView);
        }

        foreach ([
            '.document-shell',
            '.document-header',
            '.document-status-badge',
            '#items td.total-line',
            '#items td.total-value',
            '@page',
            '@media print',
        ] as $invoiceCssContract) {
            $this->assertStringContainsString($invoiceCssContract, $invoiceCss);
        }

        foreach ([
            '.document-shell',
            '.document-header',
            '.document-status-badge',
            '#items td.total-line',
            '#items td.total-value',
        ] as $emailCssContract) {
            $this->assertStringContainsString($emailCssContract, $invoiceEmailCss);
        }

        foreach ([
            '.receipt-shell',
            '.receipt-status-badge',
            '#receipt_general_info',
            '#receipt_items th',
            '#receipt_items .total-value',
            '@media print',
        ] as $receiptCssContract) {
            $this->assertStringContainsString($receiptCssContract, $receiptCss);
        }
    }

    public function testBrandingConfigSurfaceAndControllerContractsExist(): void
    {
        $manage         = file_get_contents(__DIR__ . '/../../app/Views/configs/manage.php');
        $brandingConfig = file_get_contents(__DIR__ . '/../../app/Views/configs/branding_config.php');
        $controller     = file_get_contents(__DIR__ . '/../../app/Controllers/Config.php');

        $this->assertNotFalse($manage);
        $this->assertNotFalse($brandingConfig);
        $this->assertNotFalse($controller);

        $this->assertStringContainsString('id="branding_tab"', $manage);
        $this->assertStringContainsString("view('configs/branding_config',", $manage);
        $this->assertStringContainsString("'brand_favicon_exists' => \$brand_favicon_exists", $manage);
        $this->assertStringContainsString("'brand_color_settings' => \$brand_color_settings", $manage);
        $this->assertStringContainsString("'id' => 'branding_config_form'", $brandingConfig);
        $this->assertStringContainsString('brand_short_name', $brandingConfig);
        $this->assertStringContainsString('brand_favicon', $brandingConfig);
        $this->assertStringContainsString('brand_color_settings', $brandingConfig);
        $this->assertStringContainsString('brand-color-field', $brandingConfig);
        $this->assertStringContainsString('postSaveBranding', $controller);
        $this->assertStringContainsString('uploadBrandFavicon', $controller);
        $this->assertStringContainsString('postRemoveBrandFavicon', $controller);
        $this->assertStringContainsString('mime_in[brand_favicon,image/png,image/jpg,image/jpeg,image/x-icon,image/vnd.microsoft.icon]', $controller);
        $this->assertStringContainsString('ext_in[brand_favicon,png,jpg,jpeg,ico]', $controller);
    }

    public function testConfigUiModernizationContractsExist(): void
    {
        $manage         = file_get_contents(__DIR__ . '/../../app/Views/configs/manage.php');
        $infoConfig     = file_get_contents(__DIR__ . '/../../app/Views/configs/info_config.php');
        $brandingConfig = file_get_contents(__DIR__ . '/../../app/Views/configs/branding_config.php');
        $appCss         = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertNotFalse($manage);
        $this->assertNotFalse($infoConfig);
        $this->assertNotFalse($brandingConfig);
        $this->assertNotFalse($appCss);

        $this->assertStringContainsString('class="config-page"', $manage);
        $this->assertStringContainsString('class="nav nav-tabs config-tabs"', $manage);
        $this->assertStringContainsString('config-section-heading', $manage);
        $this->assertStringContainsString('config-actionbar', $manage);
        $this->assertStringContainsString('config-field-wide', $manage);
        $this->assertStringContainsString('config-dynamic-list', $manage);

        $this->assertStringContainsString('config-upload-preview-logo', $infoConfig);
        $this->assertStringContainsString('config-upload-preview-icon', $brandingConfig);
        $this->assertStringContainsString('class="config-upload-image"', $infoConfig);
        $this->assertStringContainsString('class="config-upload-image"', $brandingConfig);

        $this->assertStringContainsString('.config-page form fieldset', $appCss);
        $this->assertStringContainsString('grid-template-columns: repeat(2, minmax(18rem, 1fr));', $appCss);
        $this->assertStringContainsString('.config-actionbar', $appCss);
        $this->assertStringContainsString('position: sticky;', $appCss);
        $this->assertStringContainsString('.config-upload-preview', $appCss);
        $this->assertStringContainsString('.config-save-button', $appCss);
        $this->assertStringContainsString('.config-page .form-control', $appCss);
    }

    public function testManageTableUiModernizationContractsExist(): void
    {
        $partial         = file_get_contents(__DIR__ . '/../../app/Views/partial/manage_table.php');
        $manageScript    = file_get_contents(__DIR__ . '/../../public/js/manage_tables.js');
        $appCss          = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
        $bootstrapLocale = file_get_contents(__DIR__ . '/../../app/Views/partial/bootstrap_tables_locale.php');

        $this->assertNotFalse($partial);
        $this->assertNotFalse($manageScript);
        $this->assertNotFalse($appCss);
        $this->assertNotFalse($bootstrapLocale);

        $this->assertStringContainsString('class="manage-table-page"', $partial);
        $this->assertStringContainsString('manage-table-header', $partial);
        $this->assertStringContainsString('manage-table-primary-actions', $partial);
        $this->assertStringContainsString('manage-table-toolbar-panel', $partial);
        $this->assertStringContainsString('id="toolbar"', $partial);
        $this->assertStringContainsString('id="table_holder"', $partial);
        $this->assertStringContainsString('id="table"', $partial);

        $standardManageViews = [
            __DIR__ . '/../../app/Views/items/manage.php',
            __DIR__ . '/../../app/Views/people/manage.php',
            __DIR__ . '/../../app/Views/giftcards/manage.php',
            __DIR__ . '/../../app/Views/expenses/manage.php',
            __DIR__ . '/../../app/Views/sales/manage.php',
            __DIR__ . '/../../app/Views/cashups/manage.php',
            __DIR__ . '/../../app/Views/item_kits/manage.php',
            __DIR__ . '/../../app/Views/expenses_categories/manage.php',
            __DIR__ . '/../../app/Views/attributes/manage.php',
        ];

        foreach ($standardManageViews as $viewFile) {
            $source = file_get_contents($viewFile);
            $this->assertNotFalse($source);
            $this->assertStringContainsString("view('partial/manage_table'", $source, $viewFile);
            $this->assertStringContainsString('id="toolbar"', $source, $viewFile);
            $this->assertStringContainsString('id="table"', $source, $viewFile);
        }

        $this->assertStringContainsString('mobileResponsive: true', $manageScript);
        $this->assertStringContainsString('minWidth: 768', $manageScript);
        $this->assertStringContainsString('loadingTemplate:', $manageScript);
        $this->assertStringContainsString('onLoadError:', $manageScript);
        $this->assertStringContainsString('manage-table-load-error', $manageScript);

        $this->assertStringContainsString('formatLoadingMessage', $bootstrapLocale);
        $this->assertStringContainsString('formatNoMatches', $bootstrapLocale);

        $this->assertStringContainsString('.manage-table-page', $appCss);
        $this->assertStringContainsString('.manage-table-header', $appCss);
        $this->assertStringContainsString('.manage-table-toolbar-panel', $appCss);
        $this->assertStringContainsString('.manage-table-load-error', $appCss);
        $this->assertStringContainsString('.fixed-table-container.has-card-view', $appCss);
        $this->assertStringContainsString('.card-view', $appCss);
        $this->assertStringContainsString('.no-records-found', $appCss);
    }

    public function testGlobalTypographyAndSpacingContractsExist(): void
    {
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss   = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($appCss);

        foreach ([
            '--ui-font-family-base:',
            '--ui-line-height-base:',
            '--ui-line-height-heading:',
            '--ui-control-height:',
            '--ui-control-padding-y:',
            '--ui-control-padding-x:',
            '--ui-table-cell-padding-y:',
            '--ui-table-cell-padding-x:',
            '--ui-card-padding:',
            '--ui-section-gap:',
            '--ui-heading-color:',
        ] as $tokenContract) {
            $this->assertStringContainsString($tokenContract, $themeCss);
        }

        foreach ([
            'line-height: var(--ui-line-height-base);',
            'line-height: var(--ui-line-height-heading);',
            'min-height: var(--ui-control-height);',
            'padding: var(--ui-control-padding-y) var(--ui-control-padding-x);',
            'padding: var(--ui-table-cell-padding-y) var(--ui-table-cell-padding-x);',
            'padding: var(--ui-card-padding);',
            'color: var(--ui-heading-color);',
        ] as $themeRuleContract) {
            $this->assertStringContainsString($themeRuleContract, $themeCss);
        }

        foreach ([
            'padding-bottom: var(--ui-section-gap);',
            'padding-top: var(--ui-section-gap);',
            'gap: var(--ui-section-gap);',
            '.app-content > .container',
            '.app-content > .container-fluid',
            '#table_holder',
            '.manage-table-surface',
            '.config-page form fieldset',
            '.module-card',
        ] as $appRuleContract) {
            $this->assertStringContainsString($appRuleContract, $appCss);
        }

        $this->assertStringNotContainsString('letter-spacing: -', $themeCss . $appCss);
    }

    public function testLegacyVisualPatternsRenderAsModernComponents(): void
    {
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss   = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($appCss);

        foreach ([
            '--ui-legacy-icon-size:',
            '--ui-legacy-icon-bg:',
            '--ui-table-row-border:',
            '--ui-modal-backdrop:',
        ] as $tokenContract) {
            $this->assertStringContainsString($tokenContract, $themeCss);
        }

        foreach ([
            '.panel,',
            '.well {',
            'border: 1px solid var(--ui-border-color);',
            '.well {',
            'box-shadow: var(--ui-elevation-0);',
            '.form-group-sm .form-control,',
            '.form-group-sm .input-group-addon,',
            '.input-sm {',
            'min-height: var(--ui-control-height);',
            '.btn-xs,',
            '.btn-sm,',
            '.btn .glyphicon {',
            'height: var(--ui-legacy-icon-size);',
            'width: var(--ui-legacy-icon-size);',
            '.input-group-addon .glyphicon {',
            '.table-bordered > thead > tr > th,',
            'border-left-width: 0;',
            'border-right-width: 0;',
            '.modal-backdrop.in {',
            'background: var(--ui-modal-backdrop);',
            '.modal-content {',
            '.modal-header .close {',
        ] as $themeRuleContract) {
            $this->assertStringContainsString($themeRuleContract, $themeCss);
        }

        foreach ([
            '#filters.btn-group .btn',
            '.manage-table-primary-actions .btn .glyphicon',
            '.register-payment-panel .btn .glyphicon',
        ] as $appRuleContract) {
            $this->assertStringContainsString($appRuleContract, $appCss);
        }
    }

    public function testHomeDashboardModernizationContractsExist(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Controllers/Home.php');
        $home       = file_get_contents(__DIR__ . '/../../app/Views/home/home.php');
        $appCss     = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
        $commonLang = file_get_contents(__DIR__ . '/../../app/Language/en/Common.php');

        $this->assertNotFalse($controller);
        $this->assertNotFalse($home);
        $this->assertNotFalse($appCss);
        $this->assertNotFalse($commonLang);

        foreach ([
            'buildDashboardData',
            'getAllowedModuleIds',
            'getTodaySalesSummary',
            'getCashupSummary',
            'getLowStockSummary',
            'getReceivingSummary',
        ] as $controllerContract) {
            $this->assertStringContainsString($controllerContract, $controller);
        }

        foreach ([
            'home-dashboard',
            'home-dashboard-hero',
            'home-quick-actions',
            'home-metric-grid',
            'home-metric-card',
            'home-dashboard-layout',
            'home-alert-panel',
            'home-module-section',
            'home-recent-modules',
            'id="home_module_list"',
            'module-grid',
            'data-module-id',
        ] as $viewContract) {
            $this->assertStringContainsString($viewContract, $home);
        }

        $this->assertStringNotContainsString('home-role-shortcuts', $home);

        foreach ([
            '.home-dashboard',
            '.home-dashboard-hero',
            '.home-quick-actions',
            '.home-action',
            '.home-metric-grid',
            '.home-metric-card',
            '.home-dashboard-layout',
            '.home-alert-panel',
            '.home-module-section',
            '.home-recent-modules',
            '@media (max-width: 767px)',
        ] as $cssContract) {
            $this->assertStringContainsString($cssContract, $appCss);
        }

        foreach ([
            '"dashboard_quick_actions"',
            '"dashboard_today_sales"',
            '"dashboard_cashup_status"',
            '"dashboard_low_stock"',
            '"dashboard_receivings"',
            '"dashboard_recent_modules"',
            '"dashboard_role_shortcuts"',
            '"dashboard_all_modules"',
        ] as $languageContract) {
            $this->assertStringContainsString($languageContract, $commonLang);
        }
    }
}
