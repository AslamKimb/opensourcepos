<?php

if (!function_exists('vite_assets')) {
    /**
     * @return array{css: list<string>, js: list<string>}
     */
    function vite_assets(string $entry, ?string $manifestPath = null): array
    {
        $manifestPath ??= FCPATH . 'resources/react/.vite/manifest.json';

        if (!is_file($manifestPath)) {
            return ['css' => [], 'js' => []];
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (!is_array($manifest) || !isset($manifest[$entry]) || !is_array($manifest[$entry])) {
            return ['css' => [], 'js' => []];
        }

        $chunk = $manifest[$entry];
        $css = array_map(static fn(string $file): string => 'resources/react/' . $file, $chunk['css'] ?? []);
        $js = isset($chunk['file']) ? ['resources/react/' . $chunk['file']] : [];

        return ['css' => $css, 'js' => $js];
    }
}

if (!function_exists('vite_tags')) {
    function vite_tags(string $entry, ?string $manifestPath = null): string
    {
        $assets = vite_assets($entry, $manifestPath);
        $tags = [];

        foreach ($assets['css'] as $css) {
            $tags[] = '<link rel="stylesheet" href="' . esc(base_url($css), 'attr') . '">';
        }

        foreach ($assets['js'] as $js) {
            $tags[] = '<script type="module" src="' . esc(base_url($js), 'attr') . '"></script>';
        }

        return implode("\n", $tags);
    }
}
