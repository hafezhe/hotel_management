// plugins/bootstrap.client.ts
import { defineNuxtPlugin } from '#app'

// Import the Bootstrap JavaScript
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

export default defineNuxtPlugin(() => {
    // Since we're just importing the JS, we don't need to return anything
})