<?php
/**
 * @var object $user_info
 * @var array $allowed_modules
 * @var CodeIgniter\HTTP\IncomingRequest $request
 * @var array $config
 */

use Config\Services;

$request = Services::request();
$brand_name = brand_display_name($config);
$brand_short_name = brand_short_name($config);
$current_module = $request->getUri()->getSegment(1);
$current_module_label = $brand_short_name;
$brand_logo = basename((string)($config['company_logo'] ?? ''));
$brand_logo_src = $brand_logo !== '' ? base_url('uploads/' . rawurlencode($brand_logo)) : '';

foreach ($allowed_modules as $module) {
    if ($module->module_id === $current_module) {
        $current_module_label = lang('Module.' . $module->module_id);
        break;
    }
}
?>

<!doctype html>
<html lang="<?= $request->getLocale() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= esc($brand_name) . ' | ' . esc($brand_short_name) ?></title>
    <meta name="theme-color" content="<?= esc(brand_theme_color($config), 'attr') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= esc(brand_favicon_href($config), 'attr') ?>">
    <link rel="stylesheet" href="<?= 'resources/bootswatch/' . (empty($config['theme']) ? 'flatly' : esc($config['theme'])) . '/bootstrap.min.css' ?>">
    <?php if (ENVIRONMENT == 'development' || get_cookie('debug') == 'true' || $request->getGet('debug') == 'true') : ?>
        <!-- inject:debug:css -->
        <link rel="stylesheet" href="resources/css/jquery-ui-fe010342cb.css">
        <link rel="stylesheet" href="resources/css/bootstrap-dialog-1716ef6e7c.css">
        <link rel="stylesheet" href="resources/css/jasny-bootstrap-40bf85f3ed.css">
        <link rel="stylesheet" href="resources/css/bootstrap-datetimepicker-66374fba71.css">
        <link rel="stylesheet" href="resources/css/bootstrap-select-66d5473b84.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-ed9d1a3360.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-sticky-header-07d65e7533.css">
        <link rel="stylesheet" href="resources/css/daterangepicker-85523b7dfe.css">
        <link rel="stylesheet" href="resources/css/chartist-c19aedb81a.css">
        <link rel="stylesheet" href="resources/css/chartist-plugin-tooltip-2e0ec92e60.css">
        <link rel="stylesheet" href="resources/css/bootstrap-tagsinput-5a6d46a06c.css">
        <link rel="stylesheet" href="resources/css/bootstrap-toggle-e12db6c1f3.css">
        <link rel="stylesheet" href="resources/css/bootstrap-292fc0ad3b.autocomplete.css">
        <link rel="stylesheet" href="resources/css/invoice-c6cc6f6858.css">
        <link rel="stylesheet" href="resources/css/ospos_print-4e428ab727.css">
        <link rel="stylesheet" href="resources/css/ospos-aaeb5b4705.css">
        <link rel="stylesheet" href="resources/css/popupbox-7b616030b0.css">
        <link rel="stylesheet" href="resources/css/receipt-2ca96adfe5.css">
        <link rel="stylesheet" href="resources/css/register-add9ad56cf.css">
        <link rel="stylesheet" href="resources/css/reports-0f0856f305.css">
        <!-- endinject -->
        <!-- inject:debug:js -->
        <script src="resources/js/jquery-12e87d2f3a.js"></script>
        <script src="resources/js/jquery-4fa896f615.form.js"></script>
        <script src="resources/js/jquery-a0350e8820.validate.js"></script>
        <script src="resources/js/jquery-ui-cbc65ff85e.js"></script>
        <script src="resources/js/bootstrap-894d79839f.js"></script>
        <script src="resources/js/bootstrap-dialog-27123abb65.js"></script>
        <script src="resources/js/jasny-bootstrap-7c6d7b8adf.js"></script>
        <script src="resources/js/bootstrap-datetimepicker-25e39b7ef8.js"></script>
        <script src="resources/js/bootstrap-select-b01896a67b.js"></script>
        <script src="resources/js/bootstrap-table-bdb06552ea.js"></script>
        <script src="resources/js/bootstrap-table-export-6389dc2aa5.js"></script>
        <script src="resources/js/bootstrap-table-mobile-fc655b68ab.js"></script>
        <script src="resources/js/bootstrap-table-sticky-header-cb4d83d172.js"></script>
        <script src="resources/js/moment-d65dc6d2e6.min.js"></script>
        <script src="resources/js/daterangepicker-048c56a690.js"></script>
        <script src="resources/js/es6-promise-855125e6f5.js"></script>
        <script src="resources/js/FileSaver-e73b1946e8.js"></script>
        <script src="resources/js/html2canvas-e1d3a8d7cd.js"></script>
        <script src="resources/js/jspdf-bbbebb610c.umd.js"></script>
        <script src="resources/js/purify-87a00e56b7.js"></script>
        <script src="resources/js/jspdf-92d87e47e8.plugin.autotable.js"></script>
        <script src="resources/js/tableExport-3d506dfa61.min.js"></script>
        <script src="resources/js/chartist-8a7ecb4445.js"></script>
        <script src="resources/js/chartist-plugin-pointlabels-0a1ab6aa4e.js"></script>
        <script src="resources/js/chartist-plugin-tooltip-116cb48831.js"></script>
        <script src="resources/js/chartist-plugin-axistitle-80a1198058.js"></script>
        <script src="resources/js/chartist-plugin-barlabels-4165273742.js"></script>
        <script src="resources/js/bootstrap-notify-376bc6eb87.js"></script>
        <script src="resources/js/bootstrap-tagsinput-855a7c7670.js"></script>
        <script src="resources/js/bootstrap-toggle-1c7a19a049.js"></script>
        <script src="resources/js/clipboard-908af414ab.js"></script>
        <script src="resources/js/imgpreview-62e42c15a0.full.jquery.js"></script>
        <script src="resources/js/manage_tables-0b70f19599.js"></script>
        <script src="resources/js/nominatim-599d9d6f9c.autocomplete.js"></script>
        <!-- endinject -->
    <?php else : ?>
        <!--inject:prod:css -->
        <link rel="stylesheet" href="resources/opensourcepos-a91fa429df.min.css">
        <!-- endinject -->

        <!-- Tweaks to the UI for a particular theme should drop here  -->
        <?php if ($config['theme'] != 'flatly' && file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/css/' . esc($config['theme']) . '.css')) { ?>
            <link rel="stylesheet" href="<?= 'css/' . esc($config['theme']) . '.css' ?>">
        <?php } ?>
        <!-- inject:prod:js -->
        <script src="resources/jquery-2c872dbe60.min.js"></script>
        <script src="resources/opensourcepos-d0ba3302da.min.js"></script>
        <!-- endinject -->
    <?php endif; ?>

    <link rel="stylesheet" href="css/theme-utilitarian.css">
    <?= view('partial/brand_css', ['config' => $config]) ?>

    <?= view('partial/header_js') ?>
    <?= view('partial/lang_lines') ?>

    <style>
        html {
            overflow: auto;
        }
    </style>
</head>

<body class="ospos-app">
    <div class="wrapper app-shell">
        <header class="mobile-shellbar app-navbar" role="banner">
            <button type="button" class="navbar-toggle collapsed app-menu-toggle" data-toggle="collapse" data-target="#app-navigation" aria-controls="app-navigation" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand app-mobile-brand" href="<?= site_url() ?>">
                <?php if ($brand_logo_src !== ''): ?>
                    <img src="<?= esc($brand_logo_src, 'attr') ?>" alt="<?= esc($brand_short_name, 'attr') ?>">
                <?php else: ?>
                    <span class="brand-mark-fallback"><?= esc(brand_initial($config)) ?></span>
                <?php endif; ?>
                <span><?= esc($brand_short_name) ?></span>
            </a>

            <?= anchor('home/logout', lang('Login.logout'), ['class' => 'app-mobile-logout']) ?>
        </header>

        <div class="app-layout">
            <aside class="navbar navbar-default app-navbar app-sidebar" role="navigation" aria-label="Primary navigation">
                <a class="app-brand-panel" href="<?= site_url() ?>">
                    <span class="app-brand-mark">
                        <?php if ($brand_logo_src !== ''): ?>
                            <img src="<?= esc($brand_logo_src, 'attr') ?>" alt="<?= esc($brand_short_name, 'attr') ?>">
                        <?php else: ?>
                            <?= esc(brand_initial($config)) ?>
                        <?php endif; ?>
                    </span>
                    <span class="app-brand-copy">
                        <strong><?= esc($brand_short_name) ?></strong>
                        <small><?= esc($brand_name) ?></small>
                    </span>
                </a>

                <div id="app-navigation" class="navbar-collapse collapse app-sidebar-collapse">
                    <ul class="nav navbar-nav app-nav">
                        <?php foreach ($allowed_modules as $module): ?>
                            <?php $is_active_module = $module->module_id === $current_module; ?>
                            <li class="<?= $is_active_module ? 'active' : '' ?>">
                                <a href="<?= base_url($module->module_id) ?>" title="<?= esc(lang('Module.' . $module->module_id), 'attr') ?>" class="menu-icon" <?= $is_active_module ? 'aria-current="page"' : '' ?>>
                                    <img src="<?= base_url("images/menubar/$module->module_id.svg") ?>" alt="<?= esc(lang('Module.' . $module->module_id), 'attr') ?>">
                                    <span><?= esc(lang('Module.' . $module->module_id)) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="app-sidebar-account">
                    <?= anchor("home/changePassword/$user_info->person_id", "$user_info->first_name $user_info->last_name", ['class' => 'modal-dlg app-account-link', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Employees.change_password')]) ?>
                    <?= anchor('home/logout', lang('Login.logout'), ['class' => 'app-logout-link']) ?>
                </div>
            </aside>

            <main class="app-main" role="main">
                <div class="topbar app-commandbar">
                    <div class="container topbar-row">
                        <div class="navbar-left topbar-item topbar-clock">
                            <div id="liveclock"><?= date($config['dateformat'] . ' ' . $config['timeformat']) ?></div>
                        </div>

                        <div class="navbar-right topbar-item topbar-actions">
                            <?= anchor("home/changePassword/$user_info->person_id", "$user_info->first_name $user_info->last_name", ['class' => 'modal-dlg', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Employees.change_password')]) ?>
                            <span>&nbsp;|&nbsp;</span>
                            <?= anchor('home/logout', lang('Login.logout')) ?>
                        </div>

                        <div class="navbar-center topbar-item topbar-company">
                            <strong><?= esc($current_module_label) ?></strong>
                            <span><?= esc($config['company']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="container app-content">
            <div class="row">
