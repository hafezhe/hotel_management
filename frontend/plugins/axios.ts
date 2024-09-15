// plugins/axios.ts
import axios from 'axios'

// Function to generate random ID
function makeid(length: number) {
    let result = ''
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    const charactersLength = characters.length
    let counter = 0
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength))
        counter += 1
    }
    return result
}

export default defineNuxtPlugin((nuxtApp) => {
    const config = useRuntimeConfig()

    const api = axios.create({
        // @ts-ignore
        baseURL: config.public.apiBaseUrl, // Use the runtime config for baseURL
    })

    const read = async (url: string) => {
        return await api.get(url)
    }

    const create = async (url: string, data: any, headers = {}) => {
        console.log(data)

        if (Object.keys(data).length > 0) {
            return await api.post(url, data, { headers })
        } else {
            return 'محتوای ارسالی نادرست می باشد.'
        }
    }

    const upload = async (url: string, data: any, headers = {}) => {
        if (Object.keys(data).length > 0) {
            const formData = new FormData()

            for (const item in data) {
                if (typeof data[item] === 'object' && data[item] !== null) {
                    if (data[item].webkitRelativePath !== undefined) {
                        formData.append(item, data[item])
                    } else {
                        formData.append(item, JSON.stringify(data[item]))
                    }
                } else {
                    formData.append(item, data[item])
                }
            }

            return await api.post(url, formData, { headers })
        } else {
            return 'محتوای ارسالی نادرست می باشد.'
        }
    }

    const update = async (url: string, data: any) => {
        if (Object.keys(data).length > 0) {
            data._method = 'PATCH'
            return await api.post(url, data)
        } else {
            return 'محتوای ارسالی نادرست می باشد.'
        }
    }

    const destroy = async (url: string) => {
        const data = { _method: 'DELETE' }
        return await api.post(url, data)
    }

    nuxtApp.provide('api', {
        read,
        create,
        upload,
        update,
        destroy,
    })
})
