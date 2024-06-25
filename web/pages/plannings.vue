<template>
  <div>
    <UButton  v-if="authStore.user.roles.includes('admin')" label="Add" @click="openModal = true"/>
    <VueCal
        :events="events"
        @event-click="onEventClick"
        @cell-click="onCellClick"
        :time-from="7 * 60"
        :time-to="22 * 60"
        :hide-weekends="false"
        :disable-views="['years', 'year']"

    >

      <template #event="{ event }" :style="{ backgroundColor: 'bg-red-500' }" >
        <div class="">
          <div class="text-sm font-semibold">{{ event.title }}</div>
          <div class="text-xs">{{ event.start.toLocaleTimeString() }} - {{ event.end.toLocaleTimeString() }}</div>
        </div>
      </template>
    </VueCal>
    <UModal v-model="openModal" :ui="{ width: 'w-2/3' }">
      <UCard>
        <wizzard />
      </UCard>
    </UModal>
  </div>
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
const authStore = useAuthStore()
const fetchInitialData = async () => {
  try {
    trainerStore.fetchTrainers()
    schoolStore.fetchSchools()
    await moduleStore.fetchModules()
    meetingStore.fetchMeetings()
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
const combineTimeWithFixedDate = (time) => {
  const [hours, minutes, seconds] = time.split(':').map(Number)
  const combined = new Date('1970-01-01T00:00:00Z')
  combined.setUTCHours(hours, minutes, seconds)
  return combined
}
const formatDateTime = (date, time) => {
  return `${date} ${time}`
}
const events = computed(() => {
  return meetingStore.meetings.map(meeting => ({
    start: meeting.start_hour,
    end: meeting.end_hour,
    title: `Meeting with Trainer ID ${meeting.trainer_id}`,
    class: 'color'
  }))
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
.color {
  background-color: #f00;
}
.vuecal__event {
  border-radius: 0;
  background-color: #00b3f0;
}
</style>
