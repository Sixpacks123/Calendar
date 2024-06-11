import { defineStore } from 'pinia'

export type School = {
  id: number;
  name: string;
  address: string;
}

export const useSchoolStore = defineStore('school', () => {
  const config = useRuntimeConfig()
  const nuxtApp = useNuxtApp()

  const schools = ref<School[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchSchools = () => {
    loading.value = true
    error.value = null

    const { refresh } = useFetch<{ schools: School[] }>('/schools', {
      immediate: false,
      onResponse({ response }) {
        if (response.status === 200) {
          schools.value = response._data.schools
        } else {
          error.value = 'Error fetching schools'
        }
        loading.value = false
      },
      onError({ error }) {
        error.value = error.message || 'Error fetching schools'
        loading.value = false
      }
    })

    refresh()
  }

  const addSchool = async (school: { name: string; address: string }) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await useFetch<{ school: School }>('/schools', {
        method: 'POST',
        body: JSON.stringify(school),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      schools.value.push(data.value.school)
    } catch (err) {
      error.value = 'Error adding school'
    } finally {
      loading.value = false
    }
  }

  const updateSchool = async (school: { id: number; name: string; address: string }) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/schools/${school.id}`, {
        method: 'PUT',
        body: JSON.stringify(school),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      const index = schools.value.findIndex(s => s.id === school.id)
      if (index !== -1) {
        schools.value[index] = school
      }
    } catch (err) {
      error.value = 'Error updating school'
    } finally {
      loading.value = false
    }
  }

  const deleteSchool = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/schools/${id}`, {
        method: 'DELETE',
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      schools.value = schools.value.filter(s => s.id !== id)
    } catch (err) {
      error.value = 'Error deleting school'
    } finally {
      loading.value = false
    }
  }

  return { schools, loading, error, fetchSchools, addSchool, updateSchool, deleteSchool }
})
