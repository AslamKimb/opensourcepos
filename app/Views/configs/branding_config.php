<?php
/**
 * @var bool $brand_favicon_exists
 * @var string $brand_favicon_src
 * @var array $brand_color_settings
 * @var string $controller_name
 * @var array $config
 */
?>

<?= form_open('config/save/branding/', ['id' => 'branding_config_form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) ?>
    <div id="config_wrapper">
        <fieldset id="config_branding">
            <div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
            <ul id="branding_error_message_box" class="error_message_box"></ul>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Config.brand_short_name'), 'brand_short_name', ['class' => 'control-label col-xs-2']) ?>
                <div class="col-xs-6">
                    <?= form_input([
                        'name'      => 'brand_short_name',
                        'id'        => 'brand_short_name',
                        'class'     => 'form-control input-sm',
                        'maxlength' => 40,
                        'value'     => $config['brand_short_name'] ?? ''
                    ]) ?>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Config.brand_favicon'), 'brand_favicon', ['class' => 'control-label col-xs-2']) ?>
                <div class="col-xs-6">
                    <div class="fileinput config-upload <?= $brand_favicon_exists ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail config-upload-preview config-upload-preview-icon"></div>
                        <div class="fileinput-preview fileinput-exists thumbnail config-upload-preview config-upload-preview-icon">
                            <img alt="<?= esc(lang('Config.brand_favicon'), 'attr') ?>" src="<?= esc($brand_favicon_src, 'attr') ?>" class="config-upload-image">
                        </div>
                        <div>
                            <span class="btn btn-default btn-sm btn-file">
                                <span class="fileinput-new"><?= lang('Config.company_select_image') ?></span>
                                <span class="fileinput-exists"><?= lang('Config.company_change_image') ?></span>
                                <input type="file" name="brand_favicon" accept=".png,.jpg,.jpeg,.ico,image/png,image/jpeg,image/x-icon">
                            </span>
                            <a href="#" class="btn btn-default btn-sm fileinput-exists brand-favicon-remove" data-dismiss="fileinput"><?= lang('Config.company_remove_image') ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Config.brand_show_powered_by'), 'brand_show_powered_by', ['class' => 'control-label col-xs-2']) ?>
                <div class="col-xs-6">
                    <div class="checkbox">
                        <label>
                            <?= form_checkbox([
                                'name'    => 'brand_show_powered_by',
                                'id'      => 'brand_show_powered_by',
                                'value'   => '1',
                                'checked' => ($config['brand_show_powered_by'] ?? '1') !== '0'
                            ]) ?>
                            <?= lang('Config.brand_show_powered_by_help') ?>
                        </label>
                    </div>
                </div>
            </div>

            <?php foreach ($brand_color_settings as $key => $setting): ?>
                <?php $color = brand_normalize_hex_color($config[$key] ?? '') ?? $setting['default']; ?>
                <div class="form-group form-group-sm">
                    <?= form_label(esc($setting['label']), $key, ['class' => 'control-label col-xs-2']) ?>
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon input-sm">
                                <input type="color" value="<?= esc($color, 'attr') ?>" aria-label="<?= esc($setting['label'], 'attr') ?>">
                            </span>
                            <?= form_input([
                                'name'        => $key,
                                'id'          => $key,
                                'class'       => 'form-control input-sm brand-color-field',
                                'value'       => $color,
                                'maxlength'   => 7,
                                'pattern'     => '^#[0-9a-fA-F]{6}$',
                                'placeholder' => $setting['default']
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?= form_submit([
                'name'  => 'submit_branding',
                'id'    => 'submit_branding',
                'value' => lang('Common.submit'),
                'class' => 'btn btn-primary btn-sm pull-right'
            ]) ?>
        </fieldset>
    </div>
<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("a.brand-favicon-remove").click(function() {
            $.ajax({
                type: 'POST',
                url: '<?= "$controller_name/save/branding/remove_favicon"; ?>',
                dataType: 'json'
            });
        });

        $('.brand-color-field').each(function() {
            var textInput = $(this);
            var colorInput = textInput.closest('.input-group').find('input[type="color"]');

            colorInput.on('input', function() {
                textInput.val(this.value);
            });

            textInput.on('input', function() {
                if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
                    colorInput.val(this.value);
                }
            });
        });

        $('#branding_config_form').validate($.extend(form_support.handler, {
            errorLabelContainer: "#branding_error_message_box"
        }));
    });
</script>
