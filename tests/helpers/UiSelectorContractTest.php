<?php

use PHPUnit\Framework\TestCase;

class UiSelectorContractTest extends TestCase
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
        $this->assertStringContainsString("#error_message_box", $source);
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
        $peopleForm = file_get_contents(__DIR__ . '/../../app/Views/people/form_basic_info.php');

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
        $registerCss = file_get_contents(__DIR__ . '/../../public/css/register.css');

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
        $header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $home = file_get_contents(__DIR__ . '/../../app/Views/home/home.php');
        $office = file_get_contents(__DIR__ . '/../../app/Views/home/office.php');
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
        $registerCss = file_get_contents(__DIR__ . '/../../public/css/register.css');
        $reportsCss = file_get_contents(__DIR__ . '/../../public/css/reports.css');
        $invoiceCss = file_get_contents(__DIR__ . '/../../public/css/invoice.css');
        $receiptCss = file_get_contents(__DIR__ . '/../../public/css/receipt.css');

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
        $this->assertStringContainsString('class="mobile-shellbar app-navbar"', $header);
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

    public function testRuntimeBrandingViewsDoNotHardCodeProductBrand(): void
    {
        $header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $login = file_get_contents(__DIR__ . '/../../app/Views/login.php');
        $footer = file_get_contents(__DIR__ . '/../../app/Views/partial/footer.php');
        $home = file_get_contents(__DIR__ . '/../../app/Views/home/home.php');
        $office = file_get_contents(__DIR__ . '/../../app/Views/home/office.php');
        $invoiceEmail = file_get_contents(__DIR__ . '/../../app/Views/sales/invoice_email.php');
        $quoteEmail = file_get_contents(__DIR__ . '/../../app/Views/sales/quote_email.php');
        $workOrderEmail = file_get_contents(__DIR__ . '/../../app/Views/sales/work_order_email.php');
        $invoiceCss = file_get_contents(__DIR__ . '/../../public/css/invoice.css');
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

    public function testBrandingConfigSurfaceAndControllerContractsExist(): void
    {
        $manage = file_get_contents(__DIR__ . '/../../app/Views/configs/manage.php');
        $brandingConfig = file_get_contents(__DIR__ . '/../../app/Views/configs/branding_config.php');
        $controller = file_get_contents(__DIR__ . '/../../app/Controllers/Config.php');

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
        $manage = file_get_contents(__DIR__ . '/../../app/Views/configs/manage.php');
        $infoConfig = file_get_contents(__DIR__ . '/../../app/Views/configs/info_config.php');
        $brandingConfig = file_get_contents(__DIR__ . '/../../app/Views/configs/branding_config.php');
        $appCss = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

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
        $partial = file_get_contents(__DIR__ . '/../../app/Views/partial/manage_table.php');
        $manageScript = file_get_contents(__DIR__ . '/../../public/js/manage_tables.js');
        $appCss = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
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
        $appCss = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

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
}
