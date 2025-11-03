import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// Hàm lấy tất cả file trong thư mục con
function getAllFiles(dir, exts) {
    const files = fs.readdirSync(dir, { withFileTypes: true });
    let allFiles = [];

    for (const file of files) {
        const fullPath = path.join(dir, file.name);
        if (file.isDirectory()) {
            allFiles = allFiles.concat(getAllFiles(fullPath, exts));
        } else if (exts.some(ext => file.name.endsWith(ext))) {
            allFiles.push(fullPath);
        }
    }

    return allFiles;
}

const cssAndJsFiles = getAllFiles('resources', ['.css']);

export default defineConfig({
    plugins: [
        laravel({
            input: [...cssAndJsFiles, 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios'],
                },
            },
        },
        chunkSizeWarningLimit: 1000,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
