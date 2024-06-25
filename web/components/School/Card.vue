<template>
  <UCard title="School Information">
    <template #header>
      <div class="flex justify-end gap-4">
        <UButton icon="i-heroicons-pencil" color="orange" @click="opentEditForm = true"/>
      </div>
    </template>
    <p><strong>Name:</strong> {{ school.name }}</p>
    <p><strong>Address:</strong> {{ school.address }}</p>
  </UCard>
    <SchoolEdit :school="school" :open="opentEditForm"/>
</template>

<script setup>

const props = defineProps({
  school: {
    type: Object,
    required: true
  }
})
const opentEditForm = ref(false)
</script>
