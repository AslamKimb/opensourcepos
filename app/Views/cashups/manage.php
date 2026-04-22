<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $filters
 * @var array $selected_filters
 * @var array $config
 * @var string|null $start_date
 * @var string|null $end_date
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        // Load the preset datarange picker
        <?= view('partial/daterangepicker') ?>

        <?= view('partial/bootstrap_tables_locale') ?>

        // Override dates from server if provided
        <?php if (isset($start_date) && $start_date): ?>
        start_date = "<?= esc($start_date) ?>";
        <?php endif; ?>
        <?php if (isset($end_date) && $end_date): ?>
        end_date = "<?= esc($end_date) ?>";
        <?php endif; ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'cashup_id',
            queryParams: function() {
                return $.extend(arguments[0], {
                    "end_date": end_date,
                    "filters": $("#filters").val(),
                    "start_date": start_date
                });
            }
        });
    });
</script>
<?= view('partial/table_filter_persistence') ?>

<?= view('partial/print_receipt', ['print_after_sale' => false, 'selected_printer' => 'takings_printer']) ?>

<?php
$primary_actions = '
    <button onclick="javascript:printdoc()" class="btn btn-info btn-sm pull-right manage-table-action-secondary">
        <span class="glyphicon glyphicon-print">&nbsp;</span>' . esc(lang('Common.print')) . '
    </button>
    <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-primary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/view", 'attr') . '" title="' . esc(lang(ucfirst($controller_name) . '.new'), 'attr') . '">
        <span class="glyphicon glyphicon-tags">&nbsp;</span>' . esc(lang(ucfirst($controller_name) . '.new')) . '
    </button>';

$toolbar_actions = '
    <button id="delete" class="btn btn-default btn-sm print_hide manage-table-action-danger">
        <span class="glyphicon glyphicon-trash">&nbsp;</span>' . esc(lang('Common.delete')) . '
    </button>';

$toolbar_filters = form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']);
$toolbar_filters .= form_multiselect('filters[]', $filters, $selected_filters ?? [], [
    'id'                        => 'filters',
    'data-none-selected-text'   => lang('Common.none_selected_text'),
    'class'                     => 'selectpicker show-menu-arrow',
    'data-selected-text-format' => 'count > 1',
    'data-style'                => 'btn-default btn-sm',
    'data-width'                => 'fit'
]);
?>

<?php // Selector contracts: id="toolbar" and id="table" are rendered by partial/manage_table. ?>
<?= view('partial/manage_table', [
    'controller_name'  => $controller_name,
    'primary_actions'  => $primary_actions,
    'toolbar_actions'  => $toolbar_actions,
    'toolbar_filters'  => $toolbar_filters
]) ?>

<?= view('partial/footer') ?>
