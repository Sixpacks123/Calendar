<script lang="ts" setup>
const auth = useAuthStore()

const label = computed(() => {
   return auth.user.roles.includes('admin') ? 'Admin' : 'Trainer'
})
</script>
<template>
  <NuxtLink to="/" class="font-black text-2xl leading-tight tracking-tight flex justify-center gap-2">
    <span class="text-gray-700 dark:text-gray-100">Calendar</span>
    <UBadge class="" :label="label" />

  </NuxtLink>
</template>
