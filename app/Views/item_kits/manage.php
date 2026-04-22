<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'item_kit_id'
        });

        $('#generate_barcodes').click(function() {
            window.open(
                'index.php/item_kits/generateBarcodes/' + table_support.selected_ids().join(':'),
                '_blank' // <- This is what makes it open in a new window.
            );
        });
    });
</script>

<?php
$primary_actions = '
    <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-primary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/view", 'attr') . '" title="' . esc(lang(ucfirst($controller_name) . '.new'), 'attr') . '">
        <span class="glyphicon glyphicon-tags">&nbsp;</span>' . esc(lang(ucfirst($controller_name) . '.new')) . '
    </button>';

$toolbar_actions = '
    <button id="delete" class="btn btn-default btn-sm manage-table-action-danger">
        <span class="glyphicon glyphicon-trash">&nbsp;</span>' . esc(lang('Common.delete')) . '
    </button>
    <button id="generate_barcodes" class="btn btn-default btn-sm manage-table-action-secondary" data-href="' . esc("$controller_name/generateBarcodes", 'attr') . '">
        <span class="glyphicon glyphicon-barcode">&nbsp;</span>' . esc(lang('Items.generate_barcodes')) . '
    </button>';
?>

<?php // Selector contracts: id="toolbar" and id="table" are rendered by partial/manage_table. ?>
<?= view('partial/manage_table', [
    'controller_name' => $controller_name,
    'primary_actions' => $primary_actions,
    'toolbar_actions' => $toolbar_actions
]) ?>

<?= view('partial/footer') ?>
