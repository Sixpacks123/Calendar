<script setup lang="ts">
import {useTrainerStore} from "~/stores/trainer";

definePageMeta({
  middleware: ['auth'],
});
const modal = useModal();
const router = useRouter();
const auth = useAuthStore();
const trainer =  useTrainerStore();
const school = useSchoolStore()
const modules = useModuleStore()
const role = auth.user.roles.includes('admin') ? 'admin' : 'trainer'

school.fetchSchools()
trainer.fetchTrainers()
modules.fetchModules()
trainer.fetchMeetingsByTrainer(auth.user.id)
</script>

<template>
  <div class="grid grid-cols-12 gap-6">

    <div class="col-span-12">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          Your meetings
        </div>
        <div v-if="trainer.meetings" v-for="meetings in trainer.meetings" :key="meetings.id">
          <span class="text-lg">{{meetings.id}}</span>
          <span class="text-lg">{{meetings.start_hour}}</span>
          <span class="text-lg">{{meetings.end_hour}}</span>
        </div>

      </UCard>
    </div>
    <div class="col-span-4" v-if="role === 'admin'">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          Number of schools
        </div>
        <div class="flex justify-between items-center">
          <UIcon name="i-heroicons-academic-cap"  class="w-6 h-6"/>
          <UBadge :label="school.schools.length" />
        </div>
      </UCard>
    </div>
    <div class="col-span-4" v-if="role === 'admin'">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          Number of Module
        </div>
        <div class="flex justify-between items-center">
          <UIcon name="i-heroicons-book-open" class="w-6 h-6" />
          <UBadge :label="modules.modules.length" />
        </div>
      </UCard>
    </div>
    <div class="col-span-4" v-if="role === 'admin'">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          Number of Trainers
        </div>
        <div class="flex justify-between items-center">
          <UIcon name="i-heroicons-user"  class="w-6 h-6"/>
          <UBadge :label="trainer.trainers.length" />
        </div>
      </UCard>
  </div>
    </div>
</template>
