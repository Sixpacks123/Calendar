<template>
  <UCard>
    <UForm class="space-y-4" @submit.prevent="submitForm">
      <UFormGroup for="start_hour" label="Start Hour:">
        <UInput type="time" v-model="meeting.start_hour" id="start_hour" required />
      </UFormGroup>
      <UFormGroup for="end_hour" label="End Hour:">
        <UInput type="time" v-model="meeting.end_hour" id="end_hour" required />
      </UFormGroup>
      <UFormGroup for="break_time" label="Break Time:">
        <UInput type="number" v-model="meeting.break_time" id="break_time" required />
      </UFormGroup>
      <UFormGroup for="location" label="Location:">
        <UInput type="text" v-model="meeting.location" id="location" required />
      </UFormGroup>
      <UFormGroup for="school_id" label="School:">
        <SchoolSelector @selected="selectSchool" @created="createSchool" />
      </UFormGroup>
      <UFormGroup for="module_id" label="Module:">
        <ModuleSelector @selected="selectModule" @created="createModule" />
      </UFormGroup>
      <UFormGroup for="admin_id" label="Admin ID:">
        <UInput type="number" v-model="meeting.admin_id" id="admin_id" required />
      </UFormGroup>
      <UFormGroup for="trainer_id" label="Trainer ID:">
        <UInput type="number" v-model="meeting.trainer_id" id="trainer_id" required />
      </UFormGroup>
      <UButton type="submit" :loading="loading">Add Meeting</UButton>
    </UForm>
  </UCard>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const meeting = ref({
  start_hour: '',
  end_hour: '',
  break_time: 0,
  location: '',
  school_id: 0,
  admin_id: 0,
  trainer_id: 0,
  module_id: null as number | null
})

const loading = ref(false)
const meetingStore = useMeetingStore()
const { addMeeting } = meetingStore

const selectSchool = (schoolId: number) => {
  meeting.value.school_id = schoolId
}

const createSchool = (schoolId: number) => {
  meeting.value.school_id = schoolId
}

const selectModule = (moduleId: number) => {
  meeting.value.module_id = moduleId
}

const createModule = (moduleId: number) => {
  meeting.value.module_id = moduleId
}

const submitForm = async () => {
  loading.value = true
  try {
    await addMeeting({ ...meeting.value })
    // Réinitialiser les champs du formulaire
    meeting.value = {
      start_hour: '',
      end_hour: '',
      break_time: 0,
      location: '',
      school_id: 0,
      admin_id: 0,
      trainer_id: 0,
      module_id: null
    }
  } finally {
    loading.value = false
  }
}
</script>
