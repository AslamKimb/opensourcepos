<?php

if (!function_exists('brand_color_settings')) {
    /**
     * @return array<string, array{label:string, css:string, default:string}>
     */
    function brand_color_settings(): array
    {
        return [
            'brand_color_bg'             => ['label' => 'Background', 'css' => '--ui-bg', 'default' => '#eef3f8'],
            'brand_color_bg_subtle'      => ['label' => 'Subtle Background', 'css' => '--ui-bg-subtle', 'default' => '#f6f8fb'],
            'brand_color_surface'        => ['label' => 'Surface', 'css' => '--ui-surface', 'default' => '#ffffff'],
            'brand_color_surface_muted'  => ['label' => 'Muted Surface', 'css' => '--ui-surface-muted', 'default' => '#f8fafc'],
            'brand_color_surface_strong' => ['label' => 'Strong Surface', 'css' => '--ui-surface-strong', 'default' => '#e8eef5'],
            'brand_color_text'           => ['label' => 'Text', 'css' => '--ui-text', 'default' => '#17212f'],
            'brand_color_text_muted'     => ['label' => 'Muted Text', 'css' => '--ui-text-muted', 'default' => '#5f6f82'],
            'brand_color_accent'         => ['label' => 'Accent', 'css' => '--ui-accent', 'default' => '#0f766e'],
            'brand_color_accent_soft'    => ['label' => 'Soft Accent', 'css' => '--ui-accent-soft', 'default' => '#dff4f1'],
            'brand_color_action'         => ['label' => 'Action', 'css' => '--ui-action', 'default' => '#2563eb'],
            'brand_color_action_strong'  => ['label' => 'Strong Action', 'css' => '--ui-action-strong', 'default' => '#1d4ed8'],
            'brand_color_success'        => ['label' => 'Success', 'css' => '--ui-success', 'default' => '#137333'],
            'brand_color_warning'        => ['label' => 'Warning', 'css' => '--ui-warning', 'default' => '#b7791f'],
            'brand_color_danger'         => ['label' => 'Danger', 'css' => '--ui-danger', 'default' => '#b42318'],
            'brand_color_info'           => ['label' => 'Info', 'css' => '--ui-info', 'default' => '#2563eb'],
        ];
    }
}

if (!function_exists('brand_display_name')) {
    function brand_display_name(array $config): string
    {
        $company = trim((string)($config['company'] ?? ''));

        return $company !== '' ? $company : 'Point of Sale';
    }
}

if (!function_exists('brand_short_name')) {
    function brand_short_name(array $config): string
    {
        $shortName = trim((string)($config['brand_short_name'] ?? ''));

        return $shortName !== '' ? $shortName : brand_display_name($config);
    }
}

if (!function_exists('brand_initial')) {
    function brand_initial(array $config): string
    {
        $name = brand_short_name($config);

        return strtoupper(substr($name, 0, 1));
    }
}

if (!function_exists('brand_show_powered_by')) {
    function brand_show_powered_by(array $config): bool
    {
        return (string)($config['brand_show_powered_by'] ?? '1') !== '0';
    }
}

if (!function_exists('brand_normalize_hex_color')) {
    function brand_normalize_hex_color(?string $color): ?string
    {
        $color = trim((string)$color);
        if ($color === '') {
            return null;
        }

        if ($color[0] !== '#') {
            $color = '#' . $color;
        }

        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) === 1 ? strtolower($color) : null;
    }
}

if (!function_exists('brand_css_variables')) {
    /**
     * @return array<string, string>
     */
    function brand_css_variables(array $config): array
    {
        $variables = [];
        foreach (brand_color_settings() as $key => $setting) {
            $color = brand_normalize_hex_color($config[$key] ?? '');
            if ($color !== null) {
                $variables[$setting['css']] = $color;
            }
        }

        return $variables;
    }
}

if (!function_exists('brand_theme_color')) {
    function brand_theme_color(array $config): string
    {
        return brand_normalize_hex_color($config['brand_color_action'] ?? '') ?? '#2563eb';
    }
}

if (!function_exists('brand_favicon_href')) {
    function brand_favicon_href(array $config): string
    {
        $favicon = basename((string)($config['brand_favicon'] ?? ''));
        if ($favicon !== '') {
            return base_url('uploads/branding/' . rawurlencode($favicon));
        }

        return base_url('images/favicon.ico');
    }
}
