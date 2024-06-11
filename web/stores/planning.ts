import { defineStore } from 'pinia'
import { ref } from 'vue'

export type Planning = {
  id: number;
  title: string;
  description: string;
  start_time: string;
  end_time: string;
  location: string;
}

export const usePlanningStore = defineStore('planning', () => {
  const plannings = ref<Planning[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchPlannings = async () => {
    loading.value = true
    error.value = null
    try {
      const { data, error: fetchError } = await useFetch<Planning[]>('/plannings')
      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }
      if (data.value) {
        plannings.value = data.value
      } else {
        throw new Error('No data received')
      }
    } catch (err) {
      error.value = err.message || 'Error fetching plannings'
    } finally {
      loading.value = false
    }
  }

  const addPlanning = async (formData: FormData) => {
    loading.value = true
    error.value = null
    try {
      const { data, error: fetchError } = await useFetch('/plannings', {
        method: 'POST',
        body: formData,
      })
      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }
      plannings.value.push(data.value)
    } catch (err) {
      error.value = err.message || 'Error adding planning'
    } finally {
      loading.value = false
    }
  }

  return { plannings, loading, error, fetchPlannings, addPlanning }
})
