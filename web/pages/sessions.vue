<template>
  <div>
    <UButton @click="showModal = true">Add School</UButton>
    <UModal v-model="showModal">
      <UCard>
      <UForm class="space-y-4" @submit="submitForm">
        <UFormGroup for="name" label="Name:">
          <UInput type="text" v-model="name" id="name" required />
        </UFormGroup>
        <UFormGroup for="address" label="Address:">
          <UInput type="text" v-model="address" id="address" required />
        </UFormGroup>
        <UButton type="submit">Add School</UButton>
      </UForm>
      </UCard>
    </UModal>
    <div v-if="!loading && !error">
      <UCard v-for="school in schools" :key="school.id" title="School Information">
        <p><strong>Name:</strong> {{ school.name }}</p>
        <p><strong>Address:</strong> {{ school.address }}</p>
      </UCard>
    </div>
  </div>
</template>

<script setup>

const schoolStore = useSchoolStore()

const showModal = ref(false)
const name = ref('')
const address = ref('')

const {schools, loading, error, fetchSchools, addSchool} = schoolStore

const submitForm = async () => {
  await addSchool({name: name.value, address: address.value})
  name.value = ''
  address.value = ''
  showModal.value = false
}

onMounted(async () => {
  await nextTick()
  await fetchSchools()
})
</script>
