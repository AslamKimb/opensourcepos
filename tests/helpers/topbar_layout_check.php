<?php

$header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
$css = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

$checks = [
    'header has responsive shellbar container row class' => str_contains($header, 'class="container topbar-row app-shellbar-row"'),
    'header has shell actions class' => str_contains($header, 'class="navbar-right topbar-item topbar-actions app-shell-actions"'),
    'header has shell meta class' => str_contains($header, 'class="topbar-item topbar-company app-shell-meta"'),
    'header has unified shellbar class' => str_contains($header, 'class="app-shellbar mobile-shellbar app-navbar"'),
    'header has shell toggle class' => str_contains($header, 'class="navbar-toggle app-menu-toggle app-shell-toggle"'),
    'header removed inline topbar margin style' => !str_contains($header, 'style="margin: 0;"'),
    'header removed inline topbar text align style' => !str_contains($header, 'style="text-align: center;"'),
    'css includes topbar-row rules' => str_contains($css, '.topbar-row {'),
    'css includes shellbar-row rules' => str_contains($css, '.app-shellbar-row {'),
    'css includes topbar-company rules' => str_contains($css, '.topbar-company {'),
    'css includes topbar-actions rules' => str_contains($css, '.topbar-actions {'),
    'css includes shell overlay behavior' => str_contains($css, '.app-shell.app-shell-nav-open .app-sidebar') && str_contains($css, '.app-shell-backdrop {'),
    'css includes md breakpoint behavior' => str_contains($css, '@media (min-width: 768px)') && str_contains($css, '.app-shellbar-row {'),
];

$failed = array_keys(array_filter($checks, static fn ($ok) => !$ok));

if ($failed !== []) {
    fwrite(STDERR, "Failed checks:\n- " . implode("\n- ", $failed) . "\n");
    exit(1);
}

echo "All topbar layout checks passed.\n";
