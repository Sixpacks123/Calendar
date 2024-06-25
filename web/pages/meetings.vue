<template>
  <UTable :rows="meetings" />
</template>

<script setup>

 const meetingsStore = useMeetingStore()

 const meetings = computed(() => {
   return meetingsStore.meetings
 })
 </script>

