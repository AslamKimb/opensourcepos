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
}
