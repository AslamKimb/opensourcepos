<?php

use PHPUnit\Framework\TestCase;

class ViteHelperTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../app/Helpers/vite_helper.php';
    }

    public function testViteAssetsResolveEntryScriptAndStyles(): void
    {
        $manifest = [
            'src/react/main.tsx' => [
                'file' => 'assets/main-123.js',
                'css'  => ['assets/main-123.css'],
            ],
        ];
        $manifestPath = tempnam(sys_get_temp_dir(), 'vite-manifest-');
        file_put_contents($manifestPath, json_encode($manifest));

        $assets = vite_assets('src/react/main.tsx', $manifestPath);

        $this->assertSame(['resources/react/assets/main-123.css'], $assets['css']);
        $this->assertSame(['resources/react/assets/main-123.js'], $assets['js']);
    }

    public function testViteAssetsReturnEmptyListsWhenManifestIsMissing(): void
    {
        $assets = vite_assets('src/react/main.tsx', sys_get_temp_dir() . '/missing-vite-manifest.json');

        $this->assertSame([], $assets['css']);
        $this->assertSame([], $assets['js']);
    }
}
