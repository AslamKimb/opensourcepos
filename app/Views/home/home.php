<?php
/**
 * @var array $allowed_modules
 */

$react_modules = array_map(static fn($module): array => [
    'id'          => $module->module_id,
    'name'        => lang("Module.$module->module_id"),
    'description' => lang("Module.$module->module_id" . '_desc'),
    'url'         => base_url($module->module_id),
    'iconUrl'     => base_url("images/menubar/$module->module_id.svg"),
], $allowed_modules);
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<h3 class="text-center"><?= lang('Common.welcome_message') ?></h3>

<div id="home_module_list" data-react-root="home-modules" data-props-id="home-modules-props">
    <?php foreach($allowed_modules as $module) { ?>
        <div class="module_item" title="<?= lang("Module.$module->module_id" . '_desc') ?>">
            <a href="<?= base_url($module->module_id) ?>"><img src="<?= base_url("images/menubar/$module->module_id.svg") ?>" style="border-width: 0; height: 64px; max-width: 64px;" alt="Menubar Image"></a>
            <a href="<?= base_url($module->module_id) ?>"><?= lang("Module.$module->module_id") ?></a>
        </div>
    <?php } ?>
</div>
<script type="application/json" id="home-modules-props">
    <?= json_encode(['modules' => $react_modules], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
</script>

<?= view('partial/footer') ?>
