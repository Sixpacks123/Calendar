<template>
    <div>
      <UButton @click="showModal = true">Add Module</UButton>
      <UModal v-model="showModal">
        <UCard>
          <UForm class="space-y-4" @submit.prevent="submitForm">
            <UFormGroup for="name" label="Name:">
              <UInput type="text" v-model="name" id="name" required />
            </UFormGroup>
            <UFormGroup for="syllabus" label="Syllabus:">
              <UInput type="text" v-model="syllabus" id="syllabus" required />
            </UFormGroup>
            <UFormGroup for="hourly_rate" label="Hourly Rate:">
              <UInput type="number" v-model="hourly_rate" id="hourly_rate" required />
            </UFormGroup>
            <UFormGroup for="promotion" label="Promotion:">
              <UInput type="text" v-model="promotion" id="promotion" required />
            </UFormGroup>
            <UFormGroup for="comment" label="Comment:">
              <UInput type="text" v-model="comment" id="comment" required />
            </UFormGroup>
            <UButton type="submit">Add Module</UButton>
          </UForm>
        </UCard>
      </UModal>
      <div v-if="!loading && !error">
        <UCard v-for="module in modules" :key="module.id" title="Module Information">
          <p><strong>Name:</strong> {{ module.name }}</p>
          <p><strong>Syllabus:</strong> {{ module.syllabus }}</p>
          <p><strong>Hourly Rate:</strong> {{ module.hourly_rate }}</p>
          <p><strong>Promotion:</strong> {{ module.promotion }}</p>
          <p><strong>Comment:</strong> {{ module.comment }}</p>
        </UCard>
      </div>
      <div v-if="loading">Loading...</div>
      <div v-if="error">{{ error }}</div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, nextTick } from 'vue'
  import { useModuleStore } from '~/stores/module'
  
  const moduleStore = useModuleStore()
  
  const showModal = ref(false)
  const name = ref('')
  const syllabus = ref('')
  const hourly_rate = ref(0)
  const promotion = ref('')
  const comment = ref('')
  
  const { modules, loading, error, fetchModules, addModule } = moduleStore
  
  const submitForm = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('syllabus', syllabus.value)
    formData.append('hourly_rate', hourly_rate.value.toString())
    formData.append('promotion', promotion.value)
    formData.append('comment', comment.value)
  
    await addModule(formData)
  
    // Reset the form fields
    name.value = ''
    syllabus.value = ''
    hourly_rate.value = 0
    promotion.value = ''
    comment.value = ''
    showModal.value = false
  }
  
  onMounted(async () => {
    await nextTick()
    await fetchModules()
  })
  </script>
  