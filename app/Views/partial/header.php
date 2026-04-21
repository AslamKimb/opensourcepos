<?php
/**
 * @var object $user_info
 * @var array $allowed_modules
 * @var CodeIgniter\HTTP\IncomingRequest $request
 * @var array $config
 */

use Config\Services;

$request = Services::request();
$theme = (empty($config['theme'])
    || 'paper' == $config['theme']
    || 'readable' == $config['theme']
    ? 'flatly'
    : $config['theme']);
?>

<!doctype html>
<html lang="<?= $request->getLocale() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= esc($config['company']) . ' | ' . lang('Common.powered_by') . ' OSPOS ' . esc(config('App')->application_version) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= 'resources/bootswatch5/' . esc($theme) . '/bootstrap.min.css' ?>">
    <?php if (ENVIRONMENT == 'development' || get_cookie('debug') == 'true' || $request->getGet('debug') == 'true') : ?>
        <!-- inject:debug:css -->
        <link rel="stylesheet" href="resources/css/jquery-ui-fe010342cb.css">
        <link rel="stylesheet" href="resources/css/jasny-bootstrap-40bf85f3ed.css">
        <link rel="stylesheet" href="resources/css/bootstrap-datetimepicker-66374fba71.css">
        <link rel="stylesheet" href="resources/css/bootstrap-select-e0658d7d20.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-ed9d1a3360.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-sticky-header-07d65e7533.css">
        <link rel="stylesheet" href="resources/css/daterangepicker-85523b7dfe.css">
        <link rel="stylesheet" href="resources/css/chartist-c19aedb81a.css">
        <link rel="stylesheet" href="resources/css/chartist-plugin-tooltip-2e0ec92e60.css">
        <link rel="stylesheet" href="resources/css/bootstrap-tagsinput-5a6d46a06c.css">
        <link rel="stylesheet" href="resources/css/bootstrap5-toggle-0029b16939.css">
        <link rel="stylesheet" href="resources/css/bootstrap-icons-1f041c8521.css">
        <link rel="stylesheet" href="resources/css/bootstrap-292fc0ad3b.autocomplete.css">
        <link rel="stylesheet" href="resources/css/invoice-1636d552e2.css">
        <link rel="stylesheet" href="resources/css/ospos_print-4e428ab727.css">
        <link rel="stylesheet" href="resources/css/ospos-d462e40e4f.css">
        <link rel="stylesheet" href="resources/css/popupbox-7b616030b0.css">
        <link rel="stylesheet" href="resources/css/receipt-2ca96adfe5.css">
        <link rel="stylesheet" href="resources/css/register-3d6d6b8f15.css">
        <link rel="stylesheet" href="resources/css/reports-24c9cbf726.css">
        <!-- endinject -->
        <!-- inject:debug:js -->
        <script src="resources/js/jquery-12e87d2f3a.js"></script>
        <script src="resources/js/jquery-4fa896f615.form.js"></script>
        <script src="resources/js/jquery-a0350e8820.validate.js"></script>
        <script src="resources/js/jquery-ui-cbc65ff85e.js"></script>
        <script src="resources/js/bootstrap-a34026157a.bundle.js"></script>
        <script src="resources/js/jasny-bootstrap-7c6d7b8adf.js"></script>
        <script src="resources/js/bootstrap-datetimepicker-25e39b7ef8.js"></script>
        <script src="resources/js/bootstrap-select-146babba2b.js"></script>
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
        <script src="resources/js/bootstrap5-toggle-28250e4921.jquery.js"></script>
        <script src="resources/js/clipboard-908af414ab.js"></script>
        <script src="resources/js/imgpreview-62e42c15a0.full.jquery.js"></script>
        <script src="resources/js/bootstrap5-dialog-adapter-5971adf325.js"></script>
        <script src="resources/js/manage_tables-89b11272c5.js"></script>
        <script src="resources/js/nominatim-599d9d6f9c.autocomplete.js"></script>
        <!-- endinject -->
    <?php else : ?>
        <!--inject:prod:css -->
        <link rel="stylesheet" href="resources/opensourcepos-fb3c6704dc.min.css">
        <!-- endinject -->

        <!-- Tweaks to the UI for a particular theme should drop here  -->
        <?php if ($config['theme'] != 'flatly' && file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/css/' . esc($config['theme']) . '.css')) { ?>
            <link rel="stylesheet" href="<?= 'css/' . esc($config['theme']) . '.css' ?>">
        <?php } ?>
        <!-- inject:prod:js -->
        <script src="resources/jquery-2c872dbe60.min.js"></script>
        <script src="resources/opensourcepos-2ed1e2b6af.min.js"></script>
        <!-- endinject -->
    <?php endif; ?>

    <link rel="stylesheet" href="css/theme-utilitarian.css">

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
        <div class="topbar">
            <div class="container topbar-row">
                <div class="navbar-left topbar-item topbar-clock">
                    <span class="topbar-status-dot" aria-hidden="true"></span>
                    <div id="liveclock"><?= date($config['dateformat'] . ' ' . $config['timeformat']) ?></div>
                </div>

                <div class="navbar-right topbar-item topbar-actions">
                    <?= anchor("home/changePassword/$user_info->person_id", "$user_info->first_name $user_info->last_name", ['class' => 'modal-dlg topbar-action-link topbar-user', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Employees.change_password')]) ?>
                    <?= anchor('home/logout', lang('Login.logout'), ['id' => 'logout', 'class' => 'topbar-action-link topbar-logout']) ?>
                </div>

                <div class="navbar-center topbar-item topbar-company">
                    <span class="topbar-brand-mark" aria-hidden="true">OS</span>
                    <span class="topbar-company-copy">
                        <span class="topbar-eyebrow">Open Source POS</span>
                        <strong><?= esc($config['company']) ?></strong>
                    </span>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg app-navbar" role="navigation">
            <div class="container app-navbar-inner">
                <a class="navbar-brand app-brand" href="<?= site_url() ?>">
                    <span class="app-brand-mark" aria-hidden="true">OS</span>
                    <span class="app-brand-copy">
                        <span>OSPOS</span>
                        <small>Point of Sale</small>
                    </span>
                </a>

                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse collapse app-navbar-menu" id="appNavbar">
                    <ul class="navbar-nav ms-auto app-nav" aria-label="Main modules">
                        <?php foreach ($allowed_modules as $module): ?>
                            <?php $isActiveModule = $module->module_id == $request->getUri()->getSegment(1); ?>
                            <li class="nav-item <?= $isActiveModule ? 'active' : '' ?>">
                                <a href="<?= base_url($module->module_id) ?>" title="<?= lang("Module.$module->module_id") ?>" class="nav-link menu-icon" <?= $isActiveModule ? 'aria-current="page"' : '' ?>>
                                    <span class="module-icon-frame">
                                        <img src="<?= base_url("images/menubar/$module->module_id.svg") ?>" alt="">
                                    </span>
                                    <span class="menu-label"><?= lang('Module.' . $module->module_id) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
