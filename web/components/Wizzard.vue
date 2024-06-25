<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Create Sessions</h2>
    <UForm>
      <!-- School Section -->
      <div class="mb-4">
        <UFormGroup label="Select or Create School">
          <USelect v-model="selectedSchoolId" @change="handleSchoolChange" :options="schoolOptions" class="block w-full mt-1" placeholder="Select School.." />
        </UFormGroup>
        <div v-if="selectedSchoolId === 'new'">
          <UFormGroup label="School Name">
            <UInput v-model="school.name" placeholder="School Name" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="School Address">
            <UInput v-model="school.address" placeholder="School Address" required class="block w-full mt-1" />
          </UFormGroup>
        </div>
      </div>
      <!-- Module Section -->
      <div class="mb-4">
        <UFormGroup label="Select or Create Module">
          <USelect v-model="selectedModuleId" @change="handleModuleChange" :options="moduleOptions" placeholder="Select module .." class="block w-full mt-1" />
        </UFormGroup>
        <div v-if="selectedModuleId === 'new'">
          <UFormGroup label="Module Name">
            <UInput v-model="module.name" placeholder="Module Name" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="Module Promotion">
            <UInput v-model="module.promotion" placeholder="Module Promotion" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="Hourly Rate">
            <UInput v-model="module.hourlyRate" type="number" placeholder="Hourly Rate" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="Comment">
            <textarea v-model="module.comment" placeholder="Comment" class="block w-full mt-1"></textarea>
          </UFormGroup>
        </div>
      </div>

      <!-- Sessions Section -->
      <div class="mb-4">
        <h3 class="text-xl font-bold mb-2">Sessions</h3>
        <div v-for="(session, index) in sessions" :key="index" class="mb-4">
          <div class="flex justify-between">
            <h4 class="text-lg font-semibold mb-2">Session {{ index + 1 }}</h4>
            <UButton @click="removeSession(index)" color="red" icon="i-heroicons-trash" />
          </div>
          <UFormGroup label="Date">
            <UInput v-model="session.date" type="date" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="Start Time">
            <UInput v-model="session.startTime" type="time" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="End Time">
            <UInput v-model="session.endTime" type="time" required class="block w-full mt-1" />
          </UFormGroup>
          <UFormGroup label="Select Trainer" v-slot="{ error }" :error="!session.isTrainerAvailable">
            <USelect v-model="session.trainerId" :options="trainerOptions"   placeholder="Select one trainer" required class="block w-full mt-1" />
          </UFormGroup>
          <div v-if="index === 0">
            <UFormGroup label="Hourly Rate">
              <UInput v-model="session.hourlyRate" type="number" placeholder="Hourly Rate" required class="block w-full mt-1" />
            </UFormGroup>
            <UFormGroup label="Status">
              <USelect v-model="session.status" :options="statusOptions" required class="block w-full mt-1" />
            </UFormGroup>
            <UFormGroup label="Comment">
              <textarea v-model="session.comment" placeholder="Comment" class="block w-full mt-1"></textarea>
            </UFormGroup>
          </div>
        </div>
      </div>
      <div class="flex justify-between">
        <UButton @click="addSession" icon="i-heroicons-plus" label="Add Session" />
        <UButton label="Submit" icon="i-heroicons-paper-airplane" @click="submitForm" />
      </div>
    </UForm>
  </div>
</template>

<script setup>


const toast = useToast()

const schoolStore = useSchoolStore()
const moduleStore = useModuleStore()
const trainerStore = useTrainerStore()
const meetingStore = useMeetingStore()
const authStore = useAuthStore()

const school = ref({
  name: '',
  address: ''
})

const module = ref({
  name: '',
  promotion: '',
  hourlyRate: 0,
  comment: ''
})

const sessions = ref([{
  date: '',
  startTime: '',
  endTime: '',
  hourlyRate: 0,
  trainerId: '',
  status: 'NEEDED',
  comment: '',
  isTrainerAvailable: true
}])

const selectedSchoolId = ref(null)
const selectedModuleId = ref(null)
const schoolOptions = computed(() => {
  return [...schoolStore.schools.map(school => ({ label: school.name, value: school.id })), { label: 'Create New School', value: 'new' }]
})

const moduleOptions = computed(() => {
  return [...moduleStore.modules.map(module => ({ label: module.name, value: module.id })), { label: 'Create New Module', value: 'new' }]
})

const trainerOptions = computed(() => {
  return trainerStore.trainers.map(trainer => ({ label: `${trainer.name}`, value: trainer.id }))
})

const statusOptions = ref([
  { label: 'Needed', value: 'NEEDED' },
  { label: 'Planned', value: 'PLANNED' },
  { label: 'In Progress', value: 'IN_PROGRESS' },
  { label: 'In Review', value: 'IN_REVIEW' },
  { label: 'Evaluated', value: 'EVALUATED' },
  { label: 'Invoiced', value: 'INVOICED' }
])

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

const handleSchoolChange = () => {
  if (selectedSchoolId.value !== 'new') {
    school.value = { name: '', address: '' }
  }
}

const handleModuleChange = () => {
  if (selectedModuleId.value !== 'new') {
    module.value = { name: '', promotion: '', hourlyRate: 0, comment: '' }
  }
}

const addSession = () => {
  const lastSession = sessions.value[sessions.value.length - 1]
  sessions.value.push({
    date: '',
    startTime: '',
    endTime: '',
    hourlyRate: lastSession.hourlyRate,
    trainerId: '',
    status: lastSession.status,
    comment: lastSession.comment,
    isTrainerAvailable: true
  })
}

const removeSession = (index) => {
  if (sessions.value.length > 1) {
    sessions.value.splice(index, 1)
  } else {
    toast.add({
      id: 'remove_session_error',
      title: 'Error',
      description: 'At least one session is required.',
      icon: 'i-heroicons-exclamation-circle',
      timeout: 5000
    })
  }
}

const checkTrainerAvailability = async (session) => {
  try {
    await trainerStore.fetchMeetingsByTrainer(session.trainerId)
    const meetings = trainerStore.meetings

    const sessionStart = new Date(`${session.date}T${session.startTime}:00`)
    const sessionEnd = new Date(`${session.date}T${session.endTime}:00`)

    for (const meeting of meetings) {
      const meetingStart = new Date(meeting.start_hour)
      const meetingEnd = new Date(meeting.end_hour)

      if ((sessionStart >= meetingStart && sessionStart < meetingEnd) || (sessionEnd > meetingStart && sessionEnd <= meetingEnd)) {
        session.isTrainerAvailable = false
        return
      }
    }

    session.isTrainerAvailable = true
  } catch (error) {
    console.error('Failed to check trainer availability:', error)
    session.isTrainerAvailable = false
  }
}

const checkAllSessionsAvailability = async () => {
  for (const session of sessions.value) {
    if (session.trainerId && session.date && session.startTime && session.endTime) {
      await checkTrainerAvailability(session)
    }
  }
}

watch(() => sessions.value, checkAllSessionsAvailability, { deep: true })


onMounted(() => {
  fetchInitialData()
})

const submitForm = async () => {
  let schoolId = selectedSchoolId.value
  let moduleId = selectedModuleId.value

  try {
    if (schoolId === 'new') {
      const response = await schoolStore.addSchool(school.value)
      schoolId = response.data.id
      toast.add({
        id: 'school_created',
        title: 'Success',
        description: 'School created successfully.',
        icon: 'i-heroicons-check-circle',
        timeout: 5000
      })
    }

    if (moduleId === 'new') {
      const response = await moduleStore.addModule(module.value)
      moduleId = response.data.id
      toast.add({
        id: 'module_created',
        title: 'Success',
        description: 'Module created successfully.',
        icon: 'i-heroicons-check-circle',
        timeout: 5000
      })
    }

    const preparedSessions = []
    for (const session of sessions.value) {
      const date = session.date
      const startTime = session.startTime
      const endTime = session.endTime

      const startHour = `${date} ${startTime}:00`
      const endHour = `${date} ${endTime}:00`

      preparedSessions.push({
        start_hour: startHour,
        end_hour: endHour,
        break_time: session.hourlyRate,
        location: 'Default Location', // Add your logic here to handle the location
        school_id: parseInt(schoolId),
        module_id: parseInt(moduleId),
        trainer_id: parseInt(session.trainerId),
        admin_id: authStore.user.id
      })
    }

    await Promise.all(preparedSessions.map(session => meetingStore.addMeeting(session)))
    toast.add({
      id: 'sessions_created',
      title: 'Success',
      description: 'All sessions created successfully.',
      icon: 'i-heroicons-check-circle',
      timeout: 5000
    })
    console.log('Sessions created')
  } catch (error) {
    toast.add({
      id: 'create_sessions_error',
      title: 'Error',
      description: 'Failed to create sessions.',
      icon: 'i-heroicons-exclamation-circle',
      timeout: 5000
    })
    console.error(error)
  }
}

</script>

<style scoped>
.border-red-500 {
  border-color: red;
}
</style>
