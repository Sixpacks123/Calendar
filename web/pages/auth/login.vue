<script lang="ts" setup>
definePageMeta({
  middleware: ['guest'],
});
</script>
<template>
  <UCard class="w-full max-w-md mx-auto my-20">
    <h1 class="text-3xl font-black mb-6 leading-tight tracking-tight text">Log In Calendar</h1>
    <AuthLogin />
  </UCard>
</template>
