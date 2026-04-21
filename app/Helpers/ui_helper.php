<?php

if (!function_exists('ui_icon')) {
    /**
     * Renders a Bootstrap Icon with stable spacing for icon+label controls.
     */
    function ui_icon(string $name, string $extraClasses = '', bool $spaced = true): string
    {
        $classes = trim('bi bi-' . $name . ' ' . $extraClasses);
        $space = $spaced ? '<span class="icon-gap" aria-hidden="true"></span>' : '';

        return '<span class="' . esc($classes, 'attr') . '" aria-hidden="true"></span>' . $space;
    }
}
