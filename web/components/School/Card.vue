<template>
  <UCard title="School Information">
    <p><strong>Name:</strong> {{ school.name }}</p>
    <p><strong>Address:</strong> {{ school.address }}</p>
  </UCard>
</template>

<script setup>

const props = defineProps({
  school: {
    type: Object,
    required: true
  }
})
</script>
