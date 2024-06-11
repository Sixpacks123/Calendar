import { defineStore } from 'pinia'
import { ref } from 'vue'

export type Session = {
  id: number;
  title: string;
  description: string;
  start_time: string;
  end_time: string;
  location: string;
  school_id: number;
  admin_id: number;
  trainer_id: number;
  module_id?: number | null;
}

export const useSessionStore = defineStore('session', () => {
  const sessions = ref<Session[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchSessions = () => {
    loading.value = true
    error.value = null

    const { refresh } = useFetch<{ sessions: Session[] }>('/sessions', {
      immediate: false,
      onResponse({ response }) {
        if (response.status === 200) {
          sessions.value = response._data.sessions
        } else {
          error.value = 'Error fetching sessions'
        }
        loading.value = false
      },
      onError({ error }) {
        error.value = error.message || 'Error fetching sessions'
        loading.value = false
      }
    })

    refresh()
  }

  const addSession = async (session: Omit<Session, 'id'>) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await useFetch<{ session: Session }>('/sessions', {
        method: 'POST',
        body: JSON.stringify(session),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      sessions.value.push(data.value.session)
    } catch (err) {
      error.value = 'Error adding session'
    } finally {
      loading.value = false
    }
  }

  const updateSession = async (session: Session) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/sessions/${session.id}`, {
        method: 'PUT',
        body: JSON.stringify(session),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      const index = sessions.value.findIndex(s => s.id === session.id)
      if (index !== -1) {
        sessions.value[index] = session
      }
    } catch (err) {
      error.value = 'Error updating session'
    } finally {
      loading.value = false
    }
  }

  const deleteSession = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/sessions/${id}`, {
        method: 'DELETE',
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      sessions.value = sessions.value.filter(s => s.id !== id)
    } catch (err) {
      error.value = 'Error deleting session'
    } finally {
      loading.value = false
    }
  }

  return { sessions, loading, error, fetchSessions, addSession, updateSession, deleteSession }
})
