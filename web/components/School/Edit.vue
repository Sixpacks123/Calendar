<template>
<div>
  <UModal v-model="props.open">
    <UCard>
      <UForm @submit="submitForm">
        <UFormGroup for="name" label="Name:">
          <UInput type="text" v-model="props.school.name" id="name"  />
        </UFormGroup>
        <UFormGroup for="address" label="Address:">
          <UInput type="text" v-model="props.school.address" id="address"  />
        </UFormGroup>
        <div class="flex space-x-2">
          <UButton type="submit">Submit</UButton>
        </div>
      </UForm>
    </UCard>
  </UModal>
</div>
</template>
<script setup>

const props = defineProps({
  school: {
    type: Object,
    required: true
  },
  open: {
    type: Boolean,
  }
})
const schoolStore = useSchoolStore()

const submitForm = async () => {
  await schoolStore.updateSchool(props.school)
  props.open.value = false
}

</script>
