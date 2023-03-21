import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
// require('dotenv').config();

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    // port: parseInt(import.meta.env.VITE_PORT),
    // host: import.meta.env.VITE_HOST
    port: 5174,
    host: '0.0.0.0'
  },
  plugins: [vue(), vueJsx()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
