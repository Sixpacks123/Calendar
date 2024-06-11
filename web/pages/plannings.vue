<template>
    <div>
      <UButton @click="showModal = true">Add Planning</UButton>
      <UModal v-model="showModal">
        <UCard>
          <UForm class="space-y-4" @submit.prevent="submitForm">
            <UFormGroup for="title" label="Title:">
              <UInput type="text" v-model="title" id="title" required />
            </UFormGroup>
            <UFormGroup for="description" label="Description:">
              <UInput type="text" v-model="description" id="description" required />
            </UFormGroup>
            <UFormGroup for="start_time" label="Start Time:">
              <UInput type="datetime-local" v-model="start_time" id="start_time" required />
            </UFormGroup>
            <UFormGroup for="end_time" label="End Time:">
              <UInput type="datetime-local" v-model="end_time" id="end_time" required />
            </UFormGroup>
            <UFormGroup for="location" label="Location:">
              <UInput type="text" v-model="location" id="location" required />
            </UFormGroup>
            <UButton type="submit">Add Planning</UButton>
          </UForm>
        </UCard>
      </UModal>
      <div v-if="!loading && !error">
        <UCard v-for="planning in plannings" :key="planning.id" title="Planning Information">
          <p><strong>Title:</strong> {{ planning.title }}</p>
          <p><strong>Description:</strong> {{ planning.description }}</p>
          <p><strong>Start Time:</strong> {{ planning.start_time }}</p>
          <p><strong>End Time:</strong> {{ planning.end_time }}</p>
          <p><strong>Location:</strong> {{ planning.location }}</p>
        </UCard>
      </div>
      <div v-if="loading">Loading...</div>
      <div v-if="error">{{ error }}</div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, nextTick } from 'vue'
  import { usePlanningStore } from '~/stores/planning'
  
  const planningStore = usePlanningStore()
  
  const showModal = ref(false)
  const title = ref('')
  const description = ref('')
  const start_time = ref('')
  const end_time = ref('')
  const location = ref('')
  
  const { plannings, loading, error, fetchPlannings, addPlanning } = planningStore
  
  const submitForm = async () => {
    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('description', description.value)
    formData.append('start_time', start_time.value)
    formData.append('end_time', end_time.value)
    formData.append('location', location.value)
  
    await addPlanning(formData)
  
    // Reset the form fields
    title.value = ''
    description.value = ''
    start_time.value = ''
    end_time.value = ''
    location.value = ''
    showModal.value = false
  }
  
  onMounted(async () => {
    await nextTick()
    await fetchPlannings()
  })
  </script>
  