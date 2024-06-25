<script lang="ts" setup>
const meetingStore = useMeetingStore()
const moduleStore = useModuleStore()
const schoolStore = useSchoolStore()

onMounted(async () => {
  await meetingStore.fetchMeetings()
  await moduleStore.fetchModules()
  await schoolStore.fetchSchools()
})
</script>

<template>
  <AppHeader />

  <UContainer as="main" class="flex-grow py-4 sm:py-7 flex flex-col">
    <NuxtPage />
  </UContainer>

  <AppFooter />

  <NuxtLoadingIndicator class="!opacity-100" :throttle="0" />

  <UModals />

  <UNotifications>
    <template #title="{ title }">
      <span v-html="title" />
    </template>
    <template #description="{ description }">
      <span v-html="description" />
    </template>
  </UNotifications>
</template>

