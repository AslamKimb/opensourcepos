<?php
/**
 * Shared Bootstrap Table shell for manage/list pages.
 *
 * @var string $controller_name
 * @var string|null $module_key
 * @var string|null $page_title
 * @var string|null $page_subtitle
 * @var string|null $primary_actions
 * @var string|null $toolbar_actions
 * @var string|null $toolbar_filters
 * @var string|null $after_table
 */

$module_key = $module_key ?? $controller_name ?? '';
$page_title = $page_title ?? lang('Module.' . $module_key);
$page_subtitle = $page_subtitle ?? lang('Module.' . $module_key . '_desc');
$primary_actions = trim($primary_actions ?? '');
$toolbar_actions = trim($toolbar_actions ?? '');
$toolbar_filters = trim($toolbar_filters ?? '');
$after_table = $after_table ?? '';

if ($page_title === 'Module.' . $module_key) {
    $page_title = ucwords(str_replace('_', ' ', $module_key));
}

if ($page_subtitle === 'Module.' . $module_key . '_desc') {
    $page_subtitle = lang('Bootstrap_tables.manage_subtitle');
}
?>

<section class="manage-table-page" data-resource="<?= esc($controller_name ?? '') ?>">
    <div class="manage-table-header print_hide">
        <div class="manage-table-heading">
            <span class="manage-table-kicker"><?= esc(lang('Common.list_of')) ?></span>
            <h1><?= esc($page_title) ?></h1>
            <p><?= esc($page_subtitle) ?></p>
        </div>

        <?php if ($primary_actions !== ''): ?>
            <div id="title_bar" class="btn-toolbar print_hide manage-table-primary-actions" role="toolbar" aria-label="<?= esc(lang('Bootstrap_tables.primary_actions')) ?>">
                <?= $primary_actions ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="manage-table-toolbar-panel print_hide">
        <div id="toolbar" class="manage-table-toolbar">
            <?php if ($toolbar_actions !== ''): ?>
                <div class="manage-table-bulk-actions pull-left btn-toolbar" role="toolbar" aria-label="<?= esc(lang('Bootstrap_tables.bulk_actions')) ?>">
                    <?= $toolbar_actions ?>
                </div>
            <?php endif; ?>

            <?php if ($toolbar_filters !== ''): ?>
                <div class="manage-table-filter-actions pull-left form-inline" role="search" aria-label="<?= esc(lang('Bootstrap_tables.filters')) ?>">
                    <?= $toolbar_filters ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="manage-table-state manage-table-load-error hidden" role="alert">
        <strong><?= esc(lang('Bootstrap_tables.load_error_title')) ?></strong>
        <span><?= esc(lang('Bootstrap_tables.load_error_message')) ?></span>
        <button type="button" class="btn btn-default btn-sm" data-table-retry>
            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            <?= esc(lang('Bootstrap_tables.retry')) ?>
        </button>
    </div>

    <div id="table_holder" class="manage-table-surface">
        <table id="table"></table>
    </div>

    <?= $after_table ?>
</section>
