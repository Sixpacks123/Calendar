<script setup lang="ts">
definePageMeta({
  middleware: ['auth'],
});
const modal = useModal();
const router = useRouter();
const auth = useAuthStore();
const {data:schools} = await useFetch("/schools")

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
    {{schools}}
    <div class="col-span-9">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          User Object
        </div>
        <pre>{{ auth.user }}</pre>
      </UCard>
    </div>
  </div>
</template>
