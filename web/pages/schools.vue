<template>
  <div>
    <UButton @click="showModal = true">Add School</UButton>
    <inputSchoolForm :showModal="showModal" />
    <div v-if="!loading && !error" class="grid grid-cols-4 gap-4 pt-4">
      <SchoolCard v-for="school in schools" :key="school.id" :school="school" />
    </div>
    <div v-if="loading">Loading...</div>
    <div v-if="error">{{ error }}</div>
  </div>
</template>

<script setup>

const showModal = ref(false)
const schoolStore = useSchoolStore()
const { schools, loading, error, fetchSchools } = schoolStore

onMounted(async () => {
  await fetchSchools
})
</script>
