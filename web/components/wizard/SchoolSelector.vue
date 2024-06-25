<!-- SchoolSelector.vue -->
<template>
  <div>
    <USelect v-model="selectedSchoolId" :options="schoolOptions" placeholder="Select School" />
    <UButton @click="openSchoolForm">Create New School</UButton>
    <SchoolForm v-if="showSchoolForm" @save="handleSchoolSave" @cancel="showSchoolForm = false" />
  </div>
</template>

<script lang="ts" setup>


const schoolStore = useSchoolStore()
const selectedSchoolId = ref(null)
const schoolOptions = ref([])

const loadSchools = async () => {
  const schools = await schoolStore.fetchSchools()
  schoolOptions.value = schools.map(school => ({ label: school.name, value: school.id }))
}

const showSchoolForm = ref(false)

const openSchoolForm = () => {
  showSchoolForm.value = true
}

const handleSchoolSave = (newSchool) => {
  schoolOptions.value.push({ label: newSchool.name, value: newSchool.id })
  selectedSchoolId.value = newSchool.id
  showSchoolForm.value = false
}

loadSchools()
</script>
