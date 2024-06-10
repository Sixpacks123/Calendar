<template>
  <UModal v-model:show="props.showModal">
    <UCard>
      <UForm class="space-y-4" @submit.prevent="submitForm">
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
</template>

<script setup>
const props = defineProps({ showModal: Boolean })
const name = ref('')
const address = ref('')
const schoolStore = useSchoolStore()
const { addSchool } = schoolStore

const submitForm = async () => {
  await addSchool({ name: name.value, address: address.value })
  name.value = ''
  address.value = ''
  showModal.value = false
}

</script>
