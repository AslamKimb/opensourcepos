<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $filters
 * @var array $stock_locations
 * @var int $stock_location
 * @var array $config
 * @var string|null $start_date
 * @var string|null $end_date
 * @var array $selected_filters
 */

use App\Models\Employee;
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#generate_barcodes').click(function() {
            window.open(
                'index.php/items/generateBarcodes/' + table_support.selected_ids().join(':'),
                '_blank'
            );
        });

        // Load the preset daterange picker
        <?= view('partial/daterangepicker') ?>
        // Set the beginning of time as starting date
        $('#daterangepicker').data('daterangepicker').setStartDate("<?= date($config['dateformat'], mktime(0, 0, 0, 01, 01, 2010)) ?>");
        // Update the hidden inputs with the selected dates before submitting the search data
        var start_date = "<?= date('Y-m-d', mktime(0, 0, 0, 01, 01, 2010)) ?>";

        // Override dates from server if provided
        <?php if (isset($start_date) && $start_date): ?>
        start_date = "<?= esc($start_date) ?>";
        <?php endif; ?>
        <?php if (isset($end_date) && $end_date): ?>
        end_date = "<?= esc($end_date) ?>";
        <?php endif; ?>

        <?php
        echo view('partial/bootstrap_tables_locale');
        $employee = model(Employee::class);
        ?>

        table_support.init({
            employee_id: <?= $employee->get_logged_in_employee_info()->person_id ?>,
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'items.item_id',
            queryParams: function() {
                return $.extend(arguments[0], {
                    "start_date": start_date,
                    "end_date": end_date,
                    "stock_location": $("#stock_location").val(),
                    "filters": $("#filters").val()
                });
            },
            onLoadSuccess: function(response) {
                $('a.rollover').imgPreview({
                    imgCSS: {
                        width: 200
                    },
                    distanceFromCursor: {
                        top: 10,
                        left: -210
                    }
                })
            }
        });
    });
</script>

<?= view('partial/table_filter_persistence', ['additional_params' => ['stock_location']]) ?>

<?php
$primary_actions = '
    <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-secondary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/csvImport", 'attr') . '" title="' . esc(lang('Items.import_items_csv'), 'attr') . '">
        <span class="glyphicon glyphicon-import">&nbsp;</span>' . esc(lang('Common.import_csv')) . '
    </button>
    <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-primary" data-btn-new="' . esc(lang('Common.new'), 'attr') . '" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/view", 'attr') . '" title="' . esc(lang(ucfirst($controller_name) . '.new'), 'attr') . '">
        <span class="glyphicon glyphicon-tag">&nbsp;</span>' . esc(lang(ucfirst($controller_name) . '.new')) . '
    </button>';

$toolbar_actions = '
    <button id="delete" class="btn btn-default btn-sm print_hide manage-table-action-danger">
        <span class="glyphicon glyphicon-trash">&nbsp;</span>' . esc(lang('Common.delete')) . '
    </button>
    <button id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide manage-table-action-secondary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="items/bulkEdit" title="' . esc(lang('Items.edit_multiple_items'), 'attr') . '">
        <span class="glyphicon glyphicon-edit">&nbsp;</span>' . esc(lang('Items.bulk_edit')) . '
    </button>
    <button id="generate_barcodes" class="btn btn-default btn-sm print_hide manage-table-action-secondary" data-href="' . esc("$controller_name/generateBarcodes", 'attr') . '" title="' . esc(lang('Items.generate_barcodes'), 'attr') . '">
        <span class="glyphicon glyphicon-barcode">&nbsp;</span>' . esc(lang('Items.generate_barcodes')) . '
    </button>';

$toolbar_filters = form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']);
$toolbar_filters .= form_multiselect('filters[]', $filters, $selected_filters ?? [], [
    'id'                        => 'filters',
    'class'                     => 'selectpicker show-menu-arrow',
    'data-none-selected-text'   => lang('Common.none_selected_text'),
    'data-selected-text-format' => 'count > 1',
    'data-style'                => 'btn-default btn-sm',
    'data-width'                => 'fit'
]);

if (count($stock_locations) > 1) {
    $toolbar_filters .= form_dropdown(
        'stock_location',
        $stock_locations,
        $stock_location,
        [
            'id'         => 'stock_location',
            'class'      => 'selectpicker show-menu-arrow',
            'data-style' => 'btn-default btn-sm',
            'data-width' => 'fit'
        ]
    );
}
?>

<?php // Selector contracts: id="toolbar" and id="table" are rendered by partial/manage_table. ?>
<?= view('partial/manage_table', [
    'controller_name'  => $controller_name,
    'primary_actions'  => $primary_actions,
    'toolbar_actions'  => $toolbar_actions,
    'toolbar_filters'  => $toolbar_filters
]) ?>

<?= view('partial/footer') ?>
