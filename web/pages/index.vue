<script setup lang="ts">
import {useTrainerStore} from "~/stores/trainer";
import Wizzard from "~/components/wizzard.vue";

definePageMeta({
  middleware: ['auth'],
});
const modal = useModal();
const router = useRouter();
const auth = useAuthStore();
const trainer =  useTrainerStore();
const school = useSchoolStore()

school.fetchSchools()
trainer.fetchTrainers()
trainer.fetchMeetingsByTrainer(auth.user.id)
</script>

<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-3">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">Demo</div>

        <div class="flex gap-3">
          <UButton label="404 page" color="gray" @click="router.push('/404')" />
        </div>
      </UCard>
    </div>
    <div class="col-span-9">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          User Object
        </div>
        <pre>{{ auth.user }}</pre>
      </UCard>
    </div>

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

  {{trainer.meetings}}
</template>
