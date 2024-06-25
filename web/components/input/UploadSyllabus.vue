<template>
  <div>
    <input ref="inputRef" type="file" @change="onSelect" :accept="accept" class="hidden" />
    <UButton @click="triggerFileInput">Upload Syllabus</UButton>
    <p v-if="fileName">{{ fileName }}</p>
  </div>
</template>

<script lang="ts" setup>

const props = defineProps({
  modelValue: File | null,
  accept: {
    type: String,
    default: '.pdf,.doc,.docx',
  },
  maxSize: {
    type: Number,
    default: 2, // taille maximale en Mo
  },
})
const emit = defineEmits(['update:modelValue'])

const inputRef = ref<HTMLInputElement | null>(null)
const fileName = ref<string | null>(null)

const triggerFileInput = () => {
  if (inputRef.value) {
    inputRef.value.click()
  }
}

const onSelect = (e: Event) => {
  const target = e.target as HTMLInputElement
  const files = target.files
  if (files && files.length > 0) {
    const file = files[0]
    if (file.size > props.maxSize * 1024 * 1024) {
      useToast().add({
        title: 'File is too large.',
        color: 'red',
        icon: 'i-heroicons-exclamation-circle-solid'
      })
      emit('update:modelValue', null)
      fileName.value = null
    } else if (['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].includes(file.type)) {
      emit('update:modelValue', file)
      fileName.value = file.name
    } else {
      useToast().add({
        title: 'Invalid file type. Please upload a PDF or Word document.',
        color: 'red',
        icon: 'i-heroicons-exclamation-circle-solid'
      })
      emit('update:modelValue', null)
      fileName.value = null
    }
  }
  target.value = ''
}
</script>
