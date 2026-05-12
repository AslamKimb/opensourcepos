<?php
/**
 * @var string $controller_name
 * @var string|null $title
 * @var string|null $description
 * @var string|null $unique_id
 * @var int|null $page_size
 * @var string|null $table_headers_json
 */

$props_id = 'management-page-props';
$headers = json_decode($table_headers_json ?? '[]', true);

if (! is_array($headers)) {
    $headers = [];
}

$props = [
    'resource'    => $controller_name,
    'title'       => $title ?? lang('Module.' . $controller_name),
    'description' => $description ?? lang('Module.' . $controller_name . '_desc'),
    'uniqueId'    => $unique_id ?? '',
    'pageSize'    => $page_size ?? null,
    'headers'     => $headers,
    'legacy'      => [
        'titleBarId'    => 'title_bar',
        'toolbarId'     => 'toolbar',
        'tableHolderId' => 'table_holder',
        'summaryId'     => 'payment_summary',
    ],
];
?>

<div data-react-root="management-page" data-props-id="<?= esc($props_id, 'attr') ?>"></div>
<script id="<?= esc($props_id, 'attr') ?>" type="application/json"><?= json_encode($props, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script>
