import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [react(), tailwindcss()],
    publicDir: false,
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src/react', import.meta.url)),
        },
    },
    build: {
        outDir: 'public/resources/react',
        emptyOutDir: true,
        manifest: true,
        rolldownOptions: {
            input: 'src/react/main.tsx',
            output: {
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
            },
        },
    },
    test: {
        environment: 'jsdom',
        include: ['src/react/**/*.test.ts', 'src/react/**/*.test.tsx'],
    },
});
