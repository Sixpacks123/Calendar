import { defineStore } from 'pinia'

export type Trainer = {
    id: number;
    name: string;
    email: string;
}
export type Meeting = {
    id: number;
    start_hour: string;
    end_hour: string;
    break_time: number;
    location: string;
    school_id: number;
    admin_id: number;
    trainer_id: number;
    module_id?: number;
}
export const useTrainerStore = defineStore('trainer', () => {
    const config = useRuntimeConfig()
    const nuxtApp = useNuxtApp()
    const meetings = ref<Meeting[]>([])

    const trainers = ref<Trainer[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    const fetchTrainers = () => {
        loading.value = true
        error.value = null

        const { refresh } = useFetch<{ trainers: Trainer[] }>('/trainers', {
            immediate: true,
            onResponse({ response }) {
                if (response.status === 200) {
                    trainers.value = response._data
                } else {
                    error.value = 'Error fetching trainers'
                }
                loading.value = false
            },
            onError({ error }) {
                error.value = error.message || 'Error fetching trainers'
                loading.value = false
            }
        })

        refresh()
    }

    const addTrainer = async (trainer: { name: string; email: string }) => {
        loading.value = true
        error.value = null

        try {
            const { data, error: fetchError } = await useFetch<{ trainer: Trainer }>('/trainers', {
                method: 'POST',
                body: JSON.stringify(trainer),
                headers: {
                    'Content-Type': 'application/json',
                },
            })

            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }

            trainers.value.push(data.value.trainer)
        } catch (err) {
            error.value = 'Error adding trainer'
        } finally {
            loading.value = false
        }
    }

    const updateTrainer = async (trainer: { id: number; name: string; email: string }) => {
        loading.value = true
        error.value = null

        try {
            const { error: fetchError } = await useFetch(`/trainers/${trainer.id}`, {
                method: 'PUT',
                body: JSON.stringify(trainer),
                headers: {
                    'Content-Type': 'application/json',
                },
            })

            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }

            const index = trainers.value.findIndex(t => t.id === trainer.id)
            if (index !== -1) {
                trainers.value[index] = trainer
            }
        } catch (err) {
            error.value = 'Error updating trainer'
        } finally {
            loading.value = false
        }
    }

    const deleteTrainer = async (id: number) => {
        loading.value = true
        error.value = null

        try {
            const { error: fetchError } = await useFetch(`/trainers/${id}`, {
                method: 'DELETE',
            })

            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }

            trainers.value = trainers.value.filter(t => t.id !== id)
        } catch (err) {
            error.value = 'Error deleting trainer'
        } finally {
            loading.value = false
        }
    }

    const fetchMeetingsByTrainer = async (trainerId: number) => {
        loading.value = true
        error.value = null

        try {
            const { data, error: fetchError } = await useFetch<{ meetings: Meeting[] }>(`/meetings/trainer/${trainerId}`, {
                method: 'GET',
            })

            if (fetchError.value) {
                throw new Error(fetchError.value.message)
            }

            meetings.value = data.value
        } catch (err) {
            error.value = 'Error fetching meetings for trainer'
        } finally {
            loading.value = false
        }
    }
    return { trainers, meetings, loading, error, fetchTrainers, addTrainer, updateTrainer, deleteTrainer, fetchMeetingsByTrainer }
})
