// plugins/windowSize.client.ts
import { ref, onMounted, onUnmounted } from 'vue'

export default defineNuxtPlugin(() => {
    const width = ref(window.innerWidth)
    const height = ref(window.innerHeight)

    const updateSize = () => {
        width.value = window.innerWidth
        height.value = window.innerHeight
    }

    const widthCheck = (size = <Array<any>>[]) => {
        const w = width.value

        if (w < 576 && size.includes('xs')) return true
        if (w >= 576 && w < 768 && size.includes('sm')) return true
        if (w >= 768 && w < 992 && size.includes('md')) return true
        if (w >= 992 && w < 1200 && size.includes('lg')) return true
        if (w >= 1200 && size.includes('xl')) return true

        return false
    }

    onMounted(() => {
        window.addEventListener('resize', updateSize)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', updateSize)
    })

    return {
        provide: {
            windowSize: {
                width,
                height,
                widthCheck,  // Make sure `widthCheck` is provided here
            },
        },
    }
})
