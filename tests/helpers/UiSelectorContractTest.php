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
        $this->assertStringContainsString('mobileResponsive', $source);
        $this->assertStringContainsString("iconsPrefix: 'bi'", $source);
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
        $this->assertStringContainsString('navbar-expand-lg', $header);
        $this->assertStringContainsString('navbar-toggler', $header);
        $this->assertStringContainsString('data-bs-toggle="collapse"', $header);
        $this->assertStringContainsString('resources/bootswatch5/', $header);
        $this->assertStringContainsString('css/theme-utilitarian.css', $header);
        $this->assertStringContainsString('module-grid', $home);
        $this->assertStringContainsString('module-grid', $office);

        $this->assertStringContainsString('--ui-bg:', $themeCss);
        $this->assertStringContainsString('--ui-surface:', $themeCss);
        $this->assertStringContainsString('--ui-accent:', $themeCss);
        $this->assertStringContainsString('.fixed-table-container', $themeCss);
        $this->assertStringContainsString('.bootstrap-dialog', $themeCss);
        $this->assertStringContainsString('.glyphicon-edit::before', $themeCss);
        $this->assertStringContainsString('font-family: "bootstrap-icons"', $themeCss);
        $this->assertStringContainsString('.nav-tabs', $themeCss);

        $this->assertStringContainsString('.module-grid', $appCss);
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

    public function testBootstrapFiveAssetPipelineAndAdaptersArePresent(): void
    {
        $gulpfile = file_get_contents(__DIR__ . '/../../gulpfile.js');
        $package = file_get_contents(__DIR__ . '/../../package.json');
        $header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
        $dialogAdapter = file_get_contents(__DIR__ . '/../../public/js/bootstrap5-dialog-adapter.js');
        $configController = file_get_contents(__DIR__ . '/../../app/Controllers/Config.php');

        $this->assertNotFalse($gulpfile);
        $this->assertNotFalse($package);
        $this->assertNotFalse($header);
        $this->assertNotFalse($dialogAdapter);
        $this->assertNotFalse($configController);

        $this->assertStringContainsString('bootstrap5/dist/js/bootstrap.bundle', $gulpfile);
        $this->assertStringContainsString('bootswatch5/dist', $gulpfile);
        $this->assertStringContainsString('bootstrap-icons/font/bootstrap-icons', $gulpfile);
        $this->assertStringContainsString('bootstrap5-dialog-adapter.js', $gulpfile);
        $this->assertStringNotContainsString('bootstrap3-dialog', $gulpfile);
        $this->assertStringNotContainsString('bootstrap-toggle/', $gulpfile);

        $this->assertStringContainsString('"bootstrap5"', $package);
        $this->assertStringContainsString('"bootswatch5"', $package);
        $this->assertStringContainsString('"bootstrap-icons"', $package);
        $this->assertStringNotContainsString('"bootstrap"', $package);
        $this->assertStringNotContainsString('"bootswatch"', $package);
        $this->assertStringNotContainsString('"bootstrap3-dialog"', $package);

        $this->assertStringContainsString('resources/bootswatch5/', $header);
        $this->assertStringNotContainsString('resources/bootswatch/', $header);

        $this->assertStringContainsString('global.BootstrapDialog', $dialogAdapter);
        $this->assertStringContainsString('show: show', $dialogAdapter);
        $this->assertStringContainsString('bootstrap.Modal', $dialogAdapter);
        $this->assertStringContainsString('$modalBody', $dialogAdapter);
        $this->assertStringContainsString('closeAll', $dialogAdapter);

        $this->assertStringContainsString("DirectoryIterator('resources/bootswatch5')", $configController);
    }

    public function testAffectedTabsUseBootstrapFiveContracts(): void
    {
        $viewFiles = [
            __DIR__ . '/../../app/Views/configs/manage.php',
            __DIR__ . '/../../app/Views/configs/system_config.php',
            __DIR__ . '/../../app/Views/customers/form.php',
            __DIR__ . '/../../app/Views/employees/form.php',
            __DIR__ . '/../../app/Views/sales/help.php',
        ];

        foreach ($viewFiles as $viewFile) {
            $source = file_get_contents($viewFile);
            $this->assertNotFalse($source);
            $this->assertStringContainsString('data-bs-toggle="tab"', $source, $viewFile);
            $this->assertStringNotContainsString('data-toggle="tab"', $source, $viewFile);
            $this->assertStringNotContainsString('fade in active', $source, $viewFile);
        }
    }

    public function testPersonModalContractsRemainAndUseWideDialog(): void
    {
        $peopleManage = file_get_contents(__DIR__ . '/../../app/Views/people/manage.php');
        $customerForm = file_get_contents(__DIR__ . '/../../app/Views/customers/form.php');
        $employeeForm = file_get_contents(__DIR__ . '/../../app/Views/employees/form.php');
        $dialogAdapter = file_get_contents(__DIR__ . '/../../public/js/bootstrap5-dialog-adapter.js');

        $this->assertNotFalse($peopleManage);
        $this->assertNotFalse($customerForm);
        $this->assertNotFalse($employeeForm);
        $this->assertNotFalse($dialogAdapter);

        $this->assertStringContainsString('modal-dlg modal-dlg-wide', $peopleManage);
        $this->assertStringContainsString('data-btn-submit', $peopleManage);
        $this->assertStringContainsString('data-href', $peopleManage);

        $this->assertStringContainsString("'id' => 'customer_form'", $customerForm);
        $this->assertStringContainsString("'id' => 'employee_form'", $employeeForm);
        $this->assertStringContainsString('id="error_message_box"', $customerForm);
        $this->assertStringContainsString('id="error_message_box"', $employeeForm);

        $this->assertStringContainsString('handleUpdate', $dialogAdapter);
        $this->assertStringContainsString('bootstrap.Tooltip', $dialogAdapter);
    }

    public function testRegisterModeFormsUseNativeBootstrapFiveSelects(): void
    {
        $salesRegister = file_get_contents(__DIR__ . '/../../app/Views/sales/register.php');
        $receivingRegister = file_get_contents(__DIR__ . '/../../app/Views/receivings/receiving.php');

        $this->assertNotFalse($salesRegister);
        $this->assertNotFalse($receivingRegister);

        foreach ([$salesRegister, $receivingRegister] as $source) {
            $this->assertMatchesRegularExpression(
                '/form_open\("\$controller_name\/changeMode".*?form_close\(\)/s',
                $source
            );
            preg_match('/form_open\("\$controller_name\/changeMode".*?form_close\(\)/s', $source, $matches);
            $modeForm = $matches[0];

            $this->assertStringContainsString("'id' => 'mode_form'", $modeForm);
            $this->assertStringContainsString("\$('#mode_form').submit();", $modeForm);
            $this->assertStringContainsString('form-select form-select-sm', $modeForm);
            $this->assertStringNotContainsString("'class' => 'selectpicker show-menu-arrow'", $modeForm);
        }
    }

    public function testTableHeadersEmitResponsiveMobileMetadata(): void
    {
        require_once __DIR__ . '/../../app/Helpers/tabular_helper.php';

        $headers = json_decode(transform_headers([
            ['items.item_id' => 'ID'],
            ['name' => 'Name'],
            ['category' => 'Category'],
            ['cost_price' => 'Cost Price'],
            ['edit' => '', 'escape' => false],
        ]), true);

        $this->assertIsArray($headers);

        $byField = [];
        foreach ($headers as $header) {
            $byField[$header['field']] = $header;
        }

        $this->assertSame('selector', $byField['checkbox']['mobileRole']);
        $this->assertTrue($byField['checkbox']['cardVisible']);

        $this->assertSame('identifier', $byField['items.item_id']['mobileRole']);
        $this->assertFalse($byField['items.item_id']['cardVisible']);

        $this->assertSame('primary', $byField['name']['mobileRole']);
        $this->assertSame('secondary', $byField['category']['mobileRole']);
        $this->assertSame('metric', $byField['cost_price']['mobileRole']);

        $this->assertSame('action', $byField['edit']['mobileRole']);
        $this->assertTrue($byField['edit']['cardVisible']);
        $this->assertArrayHasKey('mobileOrder', $byField['name']);
    }

    public function testManageTablesScriptDefinesAdaptiveCardNormalization(): void
    {
        $source = file_get_contents(__DIR__ . '/../../public/js/manage_tables.js');

        $this->assertNotFalse($source);
        $this->assertStringContainsString('normalize_columns', $source);
        $this->assertStringContainsString('apply_mobile_card_layout', $source);
        $this->assertStringContainsString('mobileRole', $source);
        $this->assertStringContainsString('cardVisible', $source);
        $this->assertStringContainsString('ospos-card-row', $source);
        $this->assertStringContainsString('ospos-card-actions', $source);
        $this->assertStringContainsString('onPostBody', $source);
    }

    public function testResponsiveTableCssDefinesAdaptiveCardsAndIntentionalScroll(): void
    {
        $themeCss = file_get_contents(__DIR__ . '/../../public/css/theme-utilitarian.css');
        $appCss = file_get_contents(__DIR__ . '/../../public/css/ospos.css');
        $registerCss = file_get_contents(__DIR__ . '/../../public/css/register.css');
        $reportsCss = file_get_contents(__DIR__ . '/../../public/css/reports.css');

        $this->assertNotFalse($themeCss);
        $this->assertNotFalse($appCss);
        $this->assertNotFalse($registerCss);
        $this->assertNotFalse($reportsCss);

        $this->assertStringContainsString('.fixed-table-container.has-card-view', $themeCss);
        $this->assertStringContainsString('.ospos-card-row', $themeCss);
        $this->assertStringContainsString('.ospos-card-primary', $themeCss);
        $this->assertStringContainsString('.ospos-card-actions', $themeCss);
        $this->assertStringContainsString('@media (max-width: 575px)', $themeCss);

        $this->assertStringContainsString('#table_holder', $appCss);
        $this->assertStringContainsString('overflow-x: auto', $appCss);

        $this->assertStringContainsString('.register-table-container::after', $registerCss);
        $this->assertStringContainsString('.register-table-container.is-scrollable', $registerCss);

        $this->assertStringContainsString('.report-table-container', $reportsCss);
        $this->assertStringContainsString('overflow-x: auto', $reportsCss);
    }
}
