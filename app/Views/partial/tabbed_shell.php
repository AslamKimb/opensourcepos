<?php
/**
 * @var string $title
 * @var string|null $description
 * @var string|null $nav_selector
 * @var string|null $content_selector
 */

$props_id = 'tabbed-shell-props-' . bin2hex(random_bytes(4));
$props = [
    'title'           => $title,
    'description'     => $description ?? null,
    'navSelector'     => $nav_selector ?? '.nav-tabs[data-tabs="tabs"]',
    'contentSelector' => $content_selector ?? '.tab-content',
];
?>

<div data-react-root="tabbed-shell" data-props-id="<?= esc($props_id, 'attr') ?>"></div>
<script id="<?= esc($props_id, 'attr') ?>" type="application/json"><?= json_encode($props, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script>
