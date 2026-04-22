<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_add_runtime_branding extends Migration
{
    public function up(): void
    {
        helper('brand');

        $values = [
            ['key' => 'brand_short_name', 'value' => ''],
            ['key' => 'brand_favicon', 'value' => ''],
            ['key' => 'brand_show_powered_by', 'value' => '1'],
        ];

        foreach (brand_color_settings() as $key => $setting) {
            $values[] = ['key' => $key, 'value' => $setting['default']];
        }

        $this->db->table('app_config')->ignore(true)->insertBatch($values);
    }

    public function down(): void
    {
        helper('brand');

        $keys = [
            'brand_short_name',
            'brand_favicon',
            'brand_show_powered_by',
            ...array_keys(brand_color_settings()),
        ];

        $this->db->table('app_config')->whereIn('key', $keys)->delete();
    }
}
