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
            uniqueId: 'people.person_id',
            enableActions: function() {
                var email_disabled = $("td input:checkbox:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
                $("#email").prop('disabled', email_disabled);
            }
        });

        $("#email").click(function(event) {
            var recipients = $.map($("tr.selected a[href^='mailto:']"), function(element) {
                return $(element).attr('href').replace(/^mailto:/, '');
            });
            location.href = "mailto:" + recipients.join(",");
        });
    });
</script>

<?php
$primary_actions = '';
if ($controller_name === 'customers') {
    $primary_actions .= '
        <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-secondary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/csvImport", 'attr') . '" title="' . esc(lang(ucfirst($controller_name) . '.import_items_csv'), 'attr') . '">
            <span class="glyphicon glyphicon-import">&nbsp;</span>' . esc(lang('Common.import_csv')) . '
        </button>';
}

$primary_actions .= '
    <button class="btn btn-info btn-sm pull-right modal-dlg manage-table-action-primary" data-btn-submit="' . esc(lang('Common.submit'), 'attr') . '" data-href="' . esc("$controller_name/view", 'attr') . '" title="' . esc(lang(ucfirst($controller_name) . '.new'), 'attr') . '">
        <span class="glyphicon glyphicon-user">&nbsp;</span>' . esc(lang(ucfirst($controller_name) . '.new')) . '
    </button>';

$toolbar_actions = '
    <button id="delete" class="btn btn-default btn-sm manage-table-action-danger">
        <span class="glyphicon glyphicon-trash">&nbsp;</span>' . esc(lang('Common.delete')) . '
    </button>
    <button id="email" class="btn btn-default btn-sm manage-table-action-secondary">
        <span class="glyphicon glyphicon-envelope">&nbsp;</span>' . esc(lang('Common.email')) . '
    </button>';
?>

<?php // Selector contracts: id="toolbar" and id="table" are rendered by partial/manage_table. ?>
<?= view('partial/manage_table', [
    'controller_name' => $controller_name,
    'primary_actions' => $primary_actions,
    'toolbar_actions' => $toolbar_actions
]) ?>

<?= view('partial/footer') ?>
