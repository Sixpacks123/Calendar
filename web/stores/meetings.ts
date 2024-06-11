import { defineStore } from 'pinia'
import { ref } from 'vue'

export type Meeting = {
  id: number;
  start_hour: string;
  end_hour: string;
  break_time: number;
  location: string;
  school_id: number;
  admin_id: number;
  trainer_id: number;
  module_id?: number | null;
}

export const useMeetingStore = defineStore('meeting', () => {
  const config = useRuntimeConfig()
  const nuxtApp = useNuxtApp()

  const meetings = ref<Meeting[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchMeetings = () => {
    loading.value = true
    error.value = null

    const { refresh } = useFetch<{ meetings: Meeting[] }>('/meetings', {
      immediate: false,
      onResponse({ response }) {
        if (response.status === 200) {
          meetings.value = response._data
        } else {
          error.value = 'Error fetching meetings'
        }
        loading.value = false
      },
      onError({ error }) {
        error.value = error.message || 'Error fetching meetings'
        loading.value = false
      }
    })

    refresh()
  }

  const addMeeting = async (meeting: Omit<Meeting, 'id'>) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await useFetch<{ meeting: Meeting }>('/meetings', {
        method: 'POST',
        body: JSON.stringify(meeting),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      meetings.value.push(data.value)
    } catch (err) {
      error.value = 'Error adding meeting'
    } finally {
      loading.value = false
    }
  }

  const updateMeeting = async (meeting: Meeting) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/meetings/${meeting.id}`, {
        method: 'PUT',
        body: JSON.stringify(meeting),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      const index = meetings.value.findIndex(m => m.id === meeting.id)
      if (index !== -1) {
        meetings.value[index] = meeting
      }
    } catch (err) {
      error.value = 'Error updating meeting'
    } finally {
      loading.value = false
    }
  }

  const deleteMeeting = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/meetings/${id}`, {
        method: 'DELETE',
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      meetings.value = meetings.value.filter(m => m.id !== id)
    } catch (err) {
      error.value = 'Error deleting meeting'
    } finally {
      loading.value = false
    }
  }

  const assignAdmin = async (meetingId: number, adminId: number) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/meetings/${meetingId}/assign-admin`, {
        method: 'POST',
        body: JSON.stringify({ admin_id: adminId }),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      const meeting = meetings.value.find(m => m.id === meetingId)
      if (meeting) {
        meeting.admin_id = adminId
      }
    } catch (err) {
      error.value = 'Error assigning admin'
    } finally {
      loading.value = false
    }
  }

  const assignTrainer = async (meetingId: number, trainerId: number) => {
    loading.value = true
    error.value = null

    try {
      const { error: fetchError } = await useFetch(`/meetings/${meetingId}/assign-trainer`, {
        method: 'POST',
        body: JSON.stringify({ trainer_id: trainerId }),
        headers: {
          'Content-Type': 'application/json',
        },
      })

      if (fetchError.value) {
        throw new Error(fetchError.value.message)
      }

      const meeting = meetings.value.find(m => m.id === meetingId)
      if (meeting) {
        meeting.trainer_id = trainerId
      }
    } catch (err) {
      error.value = 'Error assigning trainer'
    } finally {
      loading.value = false
    }
  }

  return { meetings, loading, error, fetchMeetings, addMeeting, updateMeeting, deleteMeeting, assignAdmin, assignTrainer }
})
