<template>
  <div>
    <div v-if="step === 1">
      <h2>Select or Create School</h2>
      <InputSchoolForm @selected="handleSchoolSelection" @created="handleSchoolCreation" />
      <UButton @click="nextStep">Next</UButton>
    </div>
    <div v-else-if="step === 2">
      <h2>Select or Create Module</h2>
      <ModuleForm @selected="handleModuleSelection" @created="handleModuleCreation" />
      <UButton @click="previousStep">Back</UButton>
      <UButton @click="nextStep">Next</UButton>
    </div>
    <div v-else-if="step === 3">
      <h2>Fill Meeting Details</h2>
      <MeetingForm @submitted="handleMeetingSubmission" />
      <UButton @click="previousStep">Back</UButton>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const step = ref(1)

const selectedSchoolId = ref<number | null>(null)
const selectedModuleId = ref<number | null>(null)

const handleSchoolSelection = (schoolId: number) => {
  selectedSchoolId.value = schoolId
}

const handleSchoolCreation = (schoolId: number) => {
  selectedSchoolId.value = schoolId
}

const handleModuleSelection = (moduleId: number) => {
  selectedModuleId.value = moduleId
}

const handleModuleCreation = (moduleId: number) => {
  selectedModuleId.value = moduleId
}

const nextStep = () => {
  if (step.value < 3) {
    step.value++
  }
}

const previousStep = () => {
  if (step.value > 1) {
    step.value--
  }
}

const handleMeetingSubmission = (meetingData: any) => {
  // Handle meeting submission logic
}
</script>
