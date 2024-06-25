<template>
  <UCard>
    <UForm class="space-y-4" @submit="submitForm">
      <UFormGroup for="name" label="Name:">
        <UInput type="text" v-model="newSchool.name" id="name"  />
      </UFormGroup>
      <UFormGroup for="address" label="Address:">
        <UInput type="text" v-model="newSchool.address" id="address"  />
      </UFormGroup>
      <div class="flex space-x-2">
        <UButton type="button" @click="addSchoolToList">Add to List</UButton>
        <UButton type="submit">Submit All Schools</UButton>
      </div>
    </UForm>

    <div v-if="schools.length" class="mt-4">
      <h3>Schools to be added:</h3>
      <ul>
        <li v-for="(school, index) in schools" :key="index">
          {{ school.name }} - {{ school.address }}
          <UButton type="button" @click="removeSchool(index)">Remove</UButton>
        </li>
      </ul>
    </div>
  </UCard>
</template>

<script setup>
import { ref } from 'vue'

const newSchool = ref({ name: '', address: '' })
const schools = ref([])

const schoolStore = useSchoolStore()
const { addSchool } = schoolStore

const addSchoolToList = () => {
  if (newSchool.value.name && newSchool.value.address) {
    schools.value.push({ ...newSchool.value })
    newSchool.value.name = ''
    newSchool.value.address = ''
  }
}

const removeSchool = (index) => {
  schools.value.splice(index, 1)
}

const submitForm = async () => {
  await Promise.all(schools.value.map(school => addSchool(school)))
  schools.value = []
}
</script>
