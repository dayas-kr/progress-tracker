import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/playlists/index.js",
                "resources/js/playlists/show.js",
                "resources/js/videos/show.js",
            ],
            refresh: true,
        }),
    ],
});
