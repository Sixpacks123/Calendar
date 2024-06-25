<template>
  <div>
    <UButton @click="showModal = true">Add School</UButton>
    <UModal v-model="showModal" :ui="{width:'w-2/3'}">
      <InputSchoolForm  />
    </UModal>
    <div v-if="!loading && !error" class="grid  gap-4 pt-4">
      <SchoolCard v-for="school in schools" :key="school.id" :school="school" />
    </div>
    <div v-if="loading">Loading...</div>
    <div v-if="error">{{ error }}</div>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: ['role-admin'],
});
const showModal = ref(false)
const schoolStore = useSchoolStore()
const  schools = computed(() => schoolStore.schools)

</script>
