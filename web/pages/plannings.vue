<template>
  <div>
    <UButton label="Add" @click="openModal = true"/>
    <VueCal
        :events="events"
        @event-click="onEventClick"
        @cell-click="onCellClick"
    />
  </div>
  <UModal v-model="openModal" :ui="{ width:'w-2/3'}">
    <UCard>
      <wizzard />
    </UCard>
  </UModal>
</template>

<script setup>
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'


const toast = useToast()

const meetingStore = useMeetingStore()
const trainerStore = useTrainerStore()
const schoolStore = useSchoolStore()
const moduleStore = useModuleStore()
const openModal = ref(false)
const selectedModuleId = ref(null)

const fetchInitialData = async () => {
  try {
     trainerStore.fetchTrainers()
     schoolStore.fetchSchools()
    await moduleStore.fetchModules()
    await meetingStore.fetchMeetings()
  } catch (error) {
    toast.add({
      id: 'fetch_error',
      title: 'Error',
      description: 'Failed to fetch initial data.',
      icon: 'i-heroicons-exclamation-circle',
      timeout: 5000
    })
    console.error(error)
  }
}

const combineDateAndTime = (date, time) => {
  const [hours, minutes, seconds] = time.split(':').map(Number)
  const combined = new Date(date)
  combined.setHours(hours, minutes, seconds)
  return combined
}

const events = computed(() => {
  return meetingStore.meetings.map(meeting => {
    const today = new Date()
    const start = combineDateAndTime(today, meeting.start_hour)
    const end = combineDateAndTime(today, meeting.end_hour)
    return {
      start,
      end,
      title: `Meeting with Trainer ID ${meeting.trainer_id}`
    }
  })
})

onMounted(() => {
  fetchInitialData()
})

const onEventClick = (event) => {
  alert(`Clicked on event: ${event.title}`)
}

const onCellClick = (date) => {
  alert(`Clicked on date: ${date}`)
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
