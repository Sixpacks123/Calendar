<template>
  <UCard>
    <UForm class="space-y-4" @submit="submitForm">
      <UFormGroup for="name" label="Name:">
        <UInput type="text" v-model="newModule.name" id="name"  />
      </UFormGroup>
      <UFormGroup for="syllabus" label="Syllabus:">
        <inputUploadSyllabus v-model="newModule.syllabus" entity="module" />
      </UFormGroup>
      <UFormGroup for="hourly_rate" label="Hourly Rate:">
        <UInput type="number" v-model="newModule.hourly_rate" id="hourly_rate"  />
      </UFormGroup>
      <UFormGroup for="promotion" label="Promotion:">
        <UInput type="text" v-model="newModule.promotion" id="promotion"  />
      </UFormGroup>
      <UFormGroup for="comment" label="Comment:">
        <UInput type="text" v-model="newModule.comment" id="comment" />
      </UFormGroup>
      <div class="flex space-x-2">
        <UButton type="button" @click="addModuleToList">Add to List</UButton>
        <UButton type="submit" :loading="loading">Submit All Modules</UButton>
      </div>
    </UForm>

    <div v-if="modules.length" class="mt-4">
      <h3>Modules to be added:</h3>
      <ul>
        <li v-for="(module, index) in modules" :key="index">
          {{ module.name }} - {{ module.promotion }}
          <UButton type="button" @click="removeModule(index)">Remove</UButton>
        </li>
      </ul>
    </div>
  </UCard>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const newModule = ref({
  name: '',
  syllabus: null as File | null,
  hourly_rate: 0,
  promotion: '',
  comment: ''
})

const modules = ref([] as any[])

const moduleStore = useModuleStore()
const { addModule } = moduleStore
const loading = ref(false)

const addModuleToList = () => {
  if (newModule.value.name && newModule.value.hourly_rate && newModule.value.promotion) {
    modules.value.push({ ...newModule.value })
    newModule.value.name = ''
    newModule.value.syllabus = null
    newModule.value.hourly_rate = 0
    newModule.value.promotion = ''
    newModule.value.comment = ''
  }
}

const removeModule = (index: number) => {
  modules.value.splice(index, 1)
}

const submitForm = async () => {
  loading.value = true
  try {
    await Promise.all(modules.value.map(async module => {
      const formData = new FormData()
      formData.append('name', module.name)
      if (module.syllabus) {
        formData.append('syllabus', module.syllabus)
      }
      formData.append('hourly_rate', module.hourly_rate.toString())
      formData.append('promotion', module.promotion)
      formData.append('comment', module.comment)
      await addModule(formData)
    }))
    modules.value = []
  } finally {
    loading.value = false
  }
}
</script>
