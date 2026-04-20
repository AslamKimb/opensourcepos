<?php

use PHPUnit\Framework\TestCase;

class TopbarLayoutTest extends TestCase
{
	public function testHeaderUsesTopbarUtilityClassesWithoutInlineStyles(): void
	{
		$header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');

		$this->assertStringContainsString('class="container topbar-row"', $header);
		$this->assertStringContainsString('class="navbar-right topbar-item topbar-actions"', $header);
		$this->assertStringContainsString('class="navbar-center topbar-item topbar-company"', $header);
		$this->assertStringNotContainsString('style="margin: 0;"', $header);
		$this->assertStringNotContainsString('style="text-align: center;"', $header);
	}

	public function testTopbarStylesDefineResponsiveLayout(): void
	{
		$css = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

		$this->assertStringContainsString('.topbar-row {', $css);
		$this->assertStringContainsString('.topbar-actions {', $css);
		$this->assertStringContainsString('.topbar-company {', $css);
		$this->assertStringContainsString('@media (min-width: 768px)', $css);
	}
}
