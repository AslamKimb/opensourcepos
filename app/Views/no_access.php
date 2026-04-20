<?php
/**
 * @var string $module_name
 */

?>

<div class="utility-message utility-message-warning">
    <?= esc(lang('Error.no_permission_module') . " $module_name" . (!empty($permission_id) ? " ($permission_id)" : '')) ?>
</div>
