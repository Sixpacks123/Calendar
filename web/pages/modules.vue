<template>
  <div>
    <UButton @click="showModal = true">Add Module</UButton>
    <UModal v-model="showModal" >
      <ModuleForm :showModal="showModal" />
    </UModal>
    <div v-if="!loading && !error">
      <ModuleCard v-for="module in modules" :key="module.id" :module="module" />
    </div>
    <div v-if="loading">Loading...</div>
    <div v-if="error">{{ error }}</div>
  </div>
</template>

<script setup>

const showModal = ref(false)
const moduleStore = useModuleStore()
const { modules, loading, error, fetchModules } = moduleStore

onMounted(async () => {
  await nextTick()
  await fetchModules()
})
</script>
