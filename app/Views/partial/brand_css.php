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
            --ui-color-canvas: var(--ui-bg);
            --ui-color-canvas-subtle: var(--ui-bg-subtle);
            --ui-color-surface: var(--ui-surface);
            --ui-color-surface-muted: var(--ui-surface-muted);
            --ui-color-surface-strong: var(--ui-surface-strong);
            --ui-color-text: var(--ui-text);
            --ui-color-text-muted: var(--ui-text-muted);
            --ui-color-text-soft: var(--ui-text-soft);
            --ui-color-brand: var(--ui-accent);
            --ui-color-brand-strong: var(--ui-accent-strong);
            --ui-color-brand-soft: var(--ui-accent-soft);
            --ui-color-action: var(--ui-action);
            --ui-color-action-strong: var(--ui-action-strong);
            --ui-color-success: var(--ui-success);
            --ui-color-warning: var(--ui-warning);
            --ui-color-danger: var(--ui-danger);
            --ui-color-info: var(--ui-info);
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
