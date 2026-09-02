import { defineConfig } from 'vite';

export default defineConfig({
  publicDir: false,
  build: {
    outDir: 'public/assets/dist',
    emptyOutDir: true,
    manifest: false,
    rollupOptions: {
      input: {
        main: 'src/scripts/main.js',
        admin: 'src/scripts/admin.js'
      },
      output: {
        entryFileNames: 'assets/[name].js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name][extname]'
      }
    }
  }
});
