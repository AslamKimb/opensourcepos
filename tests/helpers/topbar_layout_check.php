<?php

$header = file_get_contents(__DIR__ . '/../../app/Views/partial/header.php');
$css = file_get_contents(__DIR__ . '/../../public/css/ospos.css');

$checks = [
    'header has responsive topbar container row class' => str_contains($header, 'class="container topbar-row"'),
    'header has semantic topbar actions class' => str_contains($header, 'class="navbar-right topbar-item topbar-actions"'),
    'header has semantic topbar company class' => str_contains($header, 'class="navbar-center topbar-item topbar-company"'),
    'header removed inline topbar margin style' => !str_contains($header, 'style="margin: 0;"'),
    'header removed inline topbar text align style' => !str_contains($header, 'style="text-align: center;"'),
    'css includes topbar-row rules' => str_contains($css, '.topbar-row {'),
    'css includes topbar-company rules' => str_contains($css, '.topbar-company {'),
    'css includes topbar-actions rules' => str_contains($css, '.topbar-actions {'),
    'css includes md breakpoint behavior' => str_contains($css, '@media (min-width: 768px)') && str_contains($css, 'grid-template-columns: 1fr auto 1fr;'),
];

$failed = array_keys(array_filter($checks, static fn ($ok) => !$ok));

if ($failed !== []) {
    fwrite(STDERR, "Failed checks:\n- " . implode("\n- ", $failed) . "\n");
    exit(1);
}

echo "All topbar layout checks passed.\n";
