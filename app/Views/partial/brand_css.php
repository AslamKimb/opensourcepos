<?php
/**
 * @var array $config
 */

$variables = brand_css_variables($config ?? []);
$theme_color = brand_theme_color($config ?? []);
$theme_rgb = sscanf($theme_color, '#%02x%02x%02x');
?>

<?php if ($variables !== []): ?>
    <style id="brand-css-vars">
        :root {
            <?php foreach ($variables as $name => $value): ?>
                <?= esc($name) ?>: <?= esc($value) ?>;
            <?php endforeach; ?>
            --ui-accent-strong: var(--ui-accent);
            --ui-text-soft: var(--ui-text-muted);
            --bs-primary: var(--ui-action);
            --bs-success: var(--ui-success);
            --bs-info: var(--ui-info);
            --bs-warning: var(--ui-warning);
            --bs-danger: var(--ui-danger);
            --bs-link-color: var(--ui-action);
            --bs-link-hover-color: var(--ui-action-strong);
            --bs-body-color: var(--ui-text);
            --bs-body-bg: var(--ui-bg);
            --bs-primary-rgb: <?= esc(implode(', ', $theme_rgb)) ?>;
        }
    </style>
<?php endif; ?>
