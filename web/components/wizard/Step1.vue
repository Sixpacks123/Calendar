<!-- Step1.vue -->
<template>
  <div>
    <h2>Step 1: Select or Create a School</h2>
    <SchoolSelector v-model="school_id" />
    <UButton @click="goToNextStep">Next</UButton>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const school_id = ref(null)
const goToNextStep = () => {
  if (school_id.value) {
    // Logique pour sauvegarder l'école sélectionnée
    emit('next')
  } else {
    alert('Please select or create a school')
  }
}
</script>
