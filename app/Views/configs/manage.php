<?php
$brand_color_settings = brand_color_settings();
$brand_favicon_exists = !empty($config['brand_favicon']);
$brand_favicon_src = brand_favicon_href($config);
$config_section = static function (string $title, string $description): string {
    return '<div class="config-section-heading">'
        . '<div><span>' . esc(lang('Config.config_section')) . '</span><h3>' . esc($title) . '</h3></div>'
        . '<p>' . esc($description) . '</p>'
        . '</div>';
};
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="config-page">
    <section class="config-page-header">
        <div>
            <span class="page-kicker"><?= esc(lang('Module.config')) ?></span>
            <h2><?= esc(lang('Config.config_page_title')) ?></h2>
            <p><?= esc(lang('Config.config_page_subtitle')) ?></p>
        </div>
        <div class="config-page-count">
            <strong>12</strong>
            <span><?= esc(lang('Config.config_sections')) ?></span>
        </div>
    </section>

    <ul class="nav nav-tabs config-tabs" data-tabs="tabs" aria-label="<?= esc(lang('Config.config_sections'), 'attr') ?>">
        <li class="active" role="presentation">
            <a data-toggle="tab" href="#info_tab" title="<?= esc(lang('Config.info_configuration'), 'attr') ?>"><?= lang('Config.info') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#general_tab" title="<?= esc(lang('Config.general_configuration'), 'attr') ?>"><?= lang('Config.general') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#branding_tab" title="<?= esc(lang('Config.branding_configuration'), 'attr') ?>"><?= lang('Config.branding') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#tax_tab" title="<?= esc(lang('Config.tax_configuration'), 'attr') ?>"><?= lang('Config.tax') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#locale_tab" title="<?= esc(lang('Config.locale_configuration'), 'attr') ?>"><?= lang('Config.locale') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#barcode_tab" title="<?= esc(lang('Config.barcode_configuration'), 'attr') ?>"><?= lang('Config.barcode') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#stock_tab" title="<?= esc(lang('Config.location_configuration'), 'attr') ?>"><?= lang('Config.location') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#receipt_tab" title="<?= esc(lang('Config.receipt_configuration'), 'attr') ?>"><?= lang('Config.receipt') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#invoice_tab" title="<?= esc(lang('Config.invoice_configuration'), 'attr') ?>"><?= lang('Config.invoice') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#reward_tab" title="<?= esc(lang('Config.reward_configuration'), 'attr') ?>"><?= lang('Config.reward') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#table_tab" title="<?= esc(lang('Config.table_configuration'), 'attr') ?>"><?= lang('Config.table') ?></a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#system_tab" title="<?= esc(lang('Config.system_conf'), 'attr') ?>"><?= lang('Config.system_conf') ?></a>
        </li>
    </ul>

<div class="tab-content config-tab-content">
    <div class="tab-pane fade in active" id="info_tab">
        <?= $config_section(lang('Config.info_configuration'), lang('Config.config_section_info_help')) ?>
        <?= view('configs/info_config') ?>
    </div>
    <div class="tab-pane" id="general_tab">
        <?= $config_section(lang('Config.general_configuration'), lang('Config.config_section_general_help')) ?>
        <?= view('configs/general_config') ?>
    </div>
    <div class="tab-pane" id="branding_tab">
        <?= $config_section(lang('Config.branding_configuration'), lang('Config.config_section_branding_help')) ?>
        <?= view('configs/branding_config', [
            'brand_color_settings' => $brand_color_settings,
            'brand_favicon_exists' => $brand_favicon_exists,
            'brand_favicon_src'    => $brand_favicon_src,
            'controller_name'      => $controller_name ?? 'config',
            'config'               => $config,
        ]) ?>
    </div>
    <div class="tab-pane" id="tax_tab">
        <?= $config_section(lang('Config.tax_configuration'), lang('Config.config_section_tax_help')) ?>
        <?= view('configs/tax_config') ?>
    </div>
    <div class="tab-pane" id="locale_tab">
        <?= $config_section(lang('Config.locale_configuration'), lang('Config.config_section_locale_help')) ?>
        <?= view('configs/locale_config') ?>
    </div>
    <div class="tab-pane" id="barcode_tab">
        <?= $config_section(lang('Config.barcode_configuration'), lang('Config.config_section_barcode_help')) ?>
        <?= view('configs/barcode_config') ?>
    </div>
    <div class="tab-pane" id="stock_tab">
        <?= $config_section(lang('Config.location_configuration'), lang('Config.config_section_stock_help')) ?>
        <?= view('configs/stock_config') ?>
    </div>
    <div class="tab-pane" id="receipt_tab">
        <?= $config_section(lang('Config.receipt_configuration'), lang('Config.config_section_receipt_help')) ?>
        <?= view('configs/receipt_config') ?>
    </div>
    <div class="tab-pane" id="invoice_tab">
        <?= $config_section(lang('Config.invoice_configuration'), lang('Config.config_section_invoice_help')) ?>
        <?= view('configs/invoice_config') ?>
    </div>
    <div class="tab-pane" id="reward_tab">
        <?= $config_section(lang('Config.reward_configuration'), lang('Config.config_section_reward_help')) ?>
        <?= view('configs/reward_config') ?>
    </div>
    <div class="tab-pane" id="table_tab">
        <?= $config_section(lang('Config.table_configuration'), lang('Config.config_section_table_help')) ?>
        <?= view('configs/table_config') ?>
    </div>
    <div class="tab-pane" id="system_tab">
        <?= $config_section(lang('Config.system_conf'), lang('Config.config_section_system_help')) ?>
        <?= view('configs/system_config') ?>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var actionNote = <?= json_encode(lang('Config.config_action_note')) ?>;
        var saveLabel = <?= json_encode(lang('Config.config_save_changes')) ?>;

        $('.config-page form input[type="submit"]').each(function() {
            var submit = $(this);

            submit.removeClass('pull-right').addClass('config-save-button');
            submit.val(saveLabel);

            if (!submit.closest('.config-actionbar').length) {
                submit.wrap('<div class="config-actionbar"></div>');
                submit.before($('<span class="config-action-note"></span>').text(actionNote));
            }
        });

        $('.config-page textarea, .config-page select[multiple], .config-page .fileinput, .config-page .bootstrap-tagsinput')
            .closest('.form-group')
            .addClass('config-field-wide');

        $('.config-page .form-group .row')
            .closest('.form-group')
            .addClass('config-field-wide');

        $('.config-page #stock_locations, .config-page #customer_rewards, .config-page #dinner_tables')
            .addClass('config-dynamic-list');
    });
</script>

<?= view('partial/footer') ?>
