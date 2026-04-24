<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TopbarLayoutTest extends TestCase
{
    public function testHeaderUsesTopbarUtilityClassesWithoutInlineStyles(): void
    {
        $header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');

        $this->assertStringContainsString('class="container-fluid topbar-row app-shellbar-row"', $header);
        $this->assertStringContainsString('class="navbar-right topbar-item topbar-actions app-shell-actions"', $header);
        $this->assertStringContainsString('class="topbar-item topbar-company app-shell-context"', $header);
        $this->assertStringContainsString('class="app-shell-account"', $header);
        $this->assertStringContainsString('class="topbar app-commandbar"', $header);
        $this->assertStringContainsString('class="app-shellbar mobile-shellbar app-navbar"', $header);
        $this->assertStringContainsString('class="navbar-toggle app-menu-toggle app-shell-toggle"', $header);
        $this->assertStringContainsString('data-target="#app-navigation"', $header);
        $this->assertStringNotContainsString('style="margin: 0;"', $header);
        $this->assertStringNotContainsString('style="text-align: center;"', $header);
    }

    public function testTopbarStylesDefineResponsiveLayout(): void
    {
        $css = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

        $this->assertStringContainsString('.topbar-row {', $css);
        $this->assertStringContainsString('.app-shellbar-row {', $css);
        $this->assertStringContainsString('.topbar-actions {', $css);
        $this->assertStringContainsString('.topbar-company {', $css);
        $this->assertStringContainsString('.app-shell-backdrop {', $css);
        $this->assertStringContainsString('.app-shell.app-shell-nav-open .app-sidebar', $css);
        $this->assertStringContainsString('.app-sidebar-collapse.collapse', $css);
        $this->assertStringContainsString('@media (min-width: 768px)', $css);
        $this->assertStringContainsString('@media (max-width: 991px)', $css);
    }
}
