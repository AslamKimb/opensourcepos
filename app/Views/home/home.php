<?php
/**
 * @var array  $allowed_modules
 * @var array  $config
 * @var array  $dashboard
 * @var object $user_info
 */
$dashboard ??= [];
$quickActions    = $dashboard['quick_actions'] ?? [];
$metrics         = $dashboard['metrics'] ?? [];
$recentModules   = $dashboard['recent_modules'] ?? [];
$moduleIndex     = $dashboard['module_index'] ?? [];
$moduleIndexJson = json_encode($moduleIndex, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$operatorName    = trim((string) ($user_info->first_name ?? ''));
$welcomeTarget   = $operatorName !== '' ? $operatorName : brand_short_name($config);
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="home-dashboard">
    <section class="home-dashboard-hero">
        <div class="home-dashboard-intro">
            <span class="home-eyebrow"><?= esc(lang('Common.dashboard')) ?></span>
            <h1><?= esc(lang('Common.welcome')) ?>, <?= esc($welcomeTarget) ?></h1>
            <p class="page-kicker"><?= esc(lang('Common.welcome_message', [brand_short_name($config)])) ?></p>
        </div>

        <?php if (! empty($quickActions)) { ?>
            <div class="home-quick-actions" aria-label="<?= esc(lang('Common.dashboard_quick_actions')) ?>">
                <?php foreach ($quickActions as $action) { ?>
                    <a class="home-action home-action-<?= esc($action['tone'], 'attr') ?>" href="<?= esc(base_url($action['href']), 'attr') ?>" data-module-id="<?= esc($action['module'], 'attr') ?>">
                        <span class="glyphicon <?= esc($action['icon'], 'attr') ?>" aria-hidden="true"></span>
                        <span><?= esc($action['label']) ?></span>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </section>

    <?php if (! empty($metrics)) { ?>
        <section class="home-metric-grid" aria-label="<?= esc(lang('Common.dashboard')) ?>">
            <?php foreach ($metrics as $metric) { ?>
                <a class="home-metric-card home-metric-card-<?= esc($metric['tone'], 'attr') ?>" href="<?= esc(base_url($metric['href']), 'attr') ?>">
                    <span class="home-metric-icon glyphicon <?= esc($metric['icon'], 'attr') ?>" aria-hidden="true"></span>
                    <span class="home-metric-label"><?= esc($metric['label']) ?></span>
                    <strong><?= esc($metric['value']) ?></strong>
                    <span class="home-metric-meta"><?= esc($metric['meta']) ?></span>
                </a>
            <?php } ?>
        </section>
    <?php } ?>

    <div class="home-dashboard-layout">
        <section class="home-alert-panel home-recent-modules">
            <header class="home-panel-heading">
                <h2><?= esc(lang('Common.dashboard_recent_modules')) ?></h2>
            </header>
            <div class="home-shortcut-list" data-home-recent-list data-empty-label="<?= esc(lang('Common.dashboard_recent_empty'), 'attr') ?>">
                <?php foreach ($recentModules as $module) { ?>
                    <?php $moduleId = $module->module_id; ?>
                    <a class="home-shortcut home-recent-module" href="<?= esc(base_url($moduleId), 'attr') ?>" data-module-id="<?= esc($moduleId, 'attr') ?>">
                        <img src="<?= esc(base_url("images/menubar/{$moduleId}.svg"), 'attr') ?>" alt="" aria-hidden="true">
                        <span><?= esc(lang("Module.{$moduleId}")) ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>
    </div>

    <section class="home-module-section">
        <div class="home-section-heading">
            <h2><?= esc(lang('Common.dashboard_all_modules')) ?></h2>
        </div>

        <div id="home_module_list" class="module-grid">
            <?php foreach ($allowed_modules as $module) { ?>
                <?php $moduleId = $module->module_id; ?>
                <div class="module_item module-card" title="<?= esc(lang("Module.{$moduleId}_desc"), 'attr') ?>">
                    <a href="<?= esc(base_url($moduleId), 'attr') ?>" data-module-id="<?= esc($moduleId, 'attr') ?>">
                        <img src="<?= esc(base_url("images/menubar/{$moduleId}.svg"), 'attr') ?>" alt="" aria-hidden="true">
                        <span><?= esc(lang("Module.{$moduleId}")) ?></span>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<script type="text/javascript">
    (function() {
        var modules = <?= $moduleIndexJson ?: '[]' ?>;
        var moduleLookup = {};
        var recentList = document.querySelector('[data-home-recent-list]');
        var storageKey = 'ospos_recent_modules';

        modules.forEach(function(module) {
            moduleLookup[module.id] = module;
        });

        function readRecentModules() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '[]');
            } catch (error) {
                return [];
            }
        }

        function writeRecentModules(moduleId) {
            var recentModules = readRecentModules().filter(function(id) {
                return id !== moduleId && moduleLookup[id];
            });

            recentModules.unshift(moduleId);

            try {
                localStorage.setItem(storageKey, JSON.stringify(recentModules.slice(0, 6)));
            } catch (error) {
                return;
            }
        }

        function renderRecentModules() {
            if (!recentList) {
                return;
            }

            var recentModules = readRecentModules().filter(function(id) {
                return moduleLookup[id];
            });

            if (recentModules.length === 0) {
                return;
            }

            recentList.innerHTML = '';
            recentModules.slice(0, 4).forEach(function(id) {
                var module = moduleLookup[id];
                var link = document.createElement('a');
                var image = document.createElement('img');
                var label = document.createElement('span');

                link.className = 'home-shortcut home-recent-module';
                link.href = module.href;
                link.setAttribute('data-module-id', module.id);
                image.src = module.iconUrl;
                image.alt = '';
                image.setAttribute('aria-hidden', 'true');
                label.textContent = module.label;

                link.appendChild(image);
                link.appendChild(label);
                recentList.appendChild(link);
            });
        }

        document.addEventListener('click', function(event) {
            var link = event.target.closest('[data-module-id]');
            if (link) {
                writeRecentModules(link.getAttribute('data-module-id'));
            }
        });

        renderRecentModules();
    }());
</script>

<?= view('partial/footer') ?>
