<template>
  <div>
    <UButton @click="showModal = true">Add Meeting</UButton>
    <UModal v-model="showModal">
      <UCard>
      <UForm class="space-y-4" @submit="submitForm">
        <UFormGroup for="start_hour" label="Start Hour:">
          <UInput type="datetime-local" v-model="start_hour" id="start_hour" required />
        </UFormGroup>
        <UFormGroup for="end_hour" label="End Hour:">
          <UInput type="datetime-local" v-model="end_hour" id="end_hour" required />
        </UFormGroup>
        <UFormGroup for="break_time" label="Break Time:">
          <UInput type="number" v-model="break_time" id="break_time" required />
        </UFormGroup>
        <UFormGroup for="location" label="Location:">
          <UInput type="text" v-model="location" id="location" required />
        </UFormGroup>
        <UFormGroup for="school_id" label="School:">
          <USelect v-model="school_id" :options="schoolOptions" option-attribute="name" value-attribute="id" id="school_id" required />
        </UFormGroup>
        <UFormGroup for="admin_id" label="Admin ID:">
          <UInput type="number" v-model="admin_id" id="admin_id" required />
        </UFormGroup>
        <UFormGroup for="trainer_id" label="Trainer ID:">
          <UInput type="number" v-model="trainer_id" id="trainer_id" required />
        </UFormGroup>
        <UButton type="submit">Add Meeting</UButton>
      </UForm>
      </UCard>
    </UModal>


      <UCard v-for="meeting in meetings" :key="meeting.id" title="Meeting Information">
        <p><strong>Start Hour:</strong> {{ meeting.start_hour }}</p>
        <p><strong>End Hour:</strong> {{ meeting.end_hour }}</p>
        <p><strong>Break Time:</strong> {{ meeting.break_time }}</p>
        <p><strong>Location:</strong> {{ meeting.location }}</p>
        <p><strong>School:</strong> {{ getSchoolName(meeting.school_id) }}</p>
        <p><strong>Admin ID:</strong> {{ meeting.admin_id }}</p>
        <p><strong>Trainer ID:</strong> {{ meeting.trainer_id }}</p>
        <p><strong>Module ID:</strong> {{ meeting.module_id }}</p>
      </UCard>
    </div>
</template>

<script setup lang="ts">

const meetingStore = useMeetingStore()
const schoolStore = useSchoolStore()

const showModal = ref(false)
const start_hour = ref('')
const end_hour = ref('')
const break_time = ref(0)
const location = ref('')
const school_id = ref<number | null>(null)
const admin_id = ref(0)
const trainer_id = ref(0)
const module_id = ref<number | null>(null)
const { meetings, loading: meetingLoading, error: meetingError, fetchMeetings, addMeeting } = meetingStore
const { schools, loading: schoolLoading, error: schoolError, fetchSchools } = schoolStore

const schoolOptions = computed(() => {
  if(!schools) return []
  console.log(schools.value)
  return schools.map(school => ({ name: school.name, id: school.id }))
})
const submitForm = async () => {
  if (school_id.value !== null) {
    await addMeeting({
      start_hour: start_hour.value,
      end_hour: end_hour.value,
      break_time: break_time.value,
      location: location.value,
      school_id: school_id.value,
      admin_id: admin_id.value,
      trainer_id: trainer_id.value,
      module_id: module_id.value,
    })
    showModal.value = false
  } else {
    console.error('School ID cannot be null')
  }
}

const getSchoolName = (id: number) => {
  const school = schools.value ? schools.value.find(s => s.id === id) : null
  return school ? school.name : 'Unknown'
}
console.log(schools)
onMounted(async () => {
  nextTick()
  await fetchMeetings()
  await fetchSchools()
})
</script>
