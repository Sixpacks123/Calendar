import { defineStore } from 'pinia'

export type Module = {
    id: number;
    name: string;
    syllabus: string;
    hourly_rate: number;
    promotion: string;
    comment: string;
}

export const useModuleStore = defineStore('module', () => {
    const modules = ref<Module[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    const fetchModules = async () => {
        loading.value = true
        error.value = null
        try {
            const { data, error: fetchError } = await useFetch<Module[]>('/modules')
            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }
            if (data.value) {
                modules.value = data.value
            } else {
                throw new Error('No data received')
            }
        } catch (err) {
            error.value = err.message || 'Error fetching modules'
        } finally {
            loading.value = false
        }
    }

    const addModule = async (formData: FormData) => {
        loading.value = true
        error.value = null
        try {
            const { data, error: fetchError } = await useFetch('/modules', {
                method: 'POST',
                body: formData,
            })
            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }
            modules.value.push(data.value)
        } catch (err) {
            error.value = err.message || 'Error adding module'
        } finally {
            loading.value = false
        }
    }

    return { modules, loading, error, fetchModules, addModule }
})
