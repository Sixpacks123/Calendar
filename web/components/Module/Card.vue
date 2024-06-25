<template>
  <UCard>
    <p><strong>Name:</strong> {{ module.name }}</p>
    <p><strong>Hourly Rate:</strong> {{ module.hourly_rate }}</p>
    <p><strong>Promotion:</strong> {{ module.promotion }}</p>
    <p><strong>Comment:</strong> {{ module.comment }}</p>
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
