<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Helpers/brand_helper.php';

class BrandHelperTest extends TestCase
{
    public function testBrandDisplayNameUsesCompanyName(): void
    {
        $this->assertSame('Brand Test Co', brand_display_name(['company' => ' Brand Test Co ']));
    }

    public function testBrandShortNameFallsBackToCompanyName(): void
    {
        $this->assertSame('Brand Test Co', brand_short_name([
            'company'          => 'Brand Test Co',
            'brand_short_name' => '',
        ]));
    }

    public function testBrandShortNameUsesConfiguredShortName(): void
    {
        $this->assertSame('BTC', brand_short_name([
            'company'          => 'Brand Test Co',
            'brand_short_name' => ' BTC ',
        ]));
    }

    public function testBrandCssVariablesKeepOnlyValidHexColors(): void
    {
        $variables = brand_css_variables([
            'brand_color_bg'      => '#101820',
            'brand_color_action'  => '0f766e',
            'brand_color_warning' => 'not-a-color',
        ]);

        $this->assertSame('#101820', $variables['--ui-bg']);
        $this->assertSame('#0f766e', $variables['--ui-action']);
        $this->assertArrayNotHasKey('--ui-warning', $variables);
    }

    public function testBrandShowPoweredByDefaultsToTrueAndHonorsZero(): void
    {
        $this->assertTrue(brand_show_powered_by([]));
        $this->assertFalse(brand_show_powered_by(['brand_show_powered_by' => '0']));
    }
}
