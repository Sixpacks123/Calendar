<template>
  <UCard>
    <p><strong>Name:</strong> {{ module.name }}</p>
    <p><strong>Hourly Rate:</strong> {{ module.hourly_rate }}</p>
    <p><strong>Promotion:</strong> {{ module.promotion }}</p>
    <p><strong>Comment:</strong> {{ module.comment }}</p>
    <a v-if="module.syllabus" :href="`/storage/${module.syllabus}`" target="_blank">Download Syllabus</a>
  </UCard>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  module: {
    type: Object,
    required: true
  }
})
</script>
