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
        $this->assertStringContainsString('module-grid', $home);
        $this->assertStringContainsString('module-grid', $office);

        $this->assertStringContainsString('--ui-bg:', $themeCss);
        $this->assertStringContainsString('--ui-surface:', $themeCss);
        $this->assertStringContainsString('--ui-accent:', $themeCss);
        $this->assertStringContainsString('.fixed-table-container', $themeCss);
        $this->assertStringContainsString('.bootstrap-dialog', $themeCss);
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
}
