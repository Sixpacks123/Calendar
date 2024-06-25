<template>
  <div>
    <h1>Meetings</h1>
    <div class="mb-4 flex gap-4">
      <UInput v-model="searchQuery" placeholder="Search meetings..." class="flex-1 mb-4" />
    </div>
    <UTable :columns="columns" :rows="filteredMeetings">
      <template #actions-data="{ row }">
        <UDropdown :items="actionItems(row)">
          <UButton color="gray" variant="ghost" icon="i-heroicons-ellipsis-horizontal-20-solid" />
        </UDropdown>
      </template>
    </UTable>

    <!-- Edit Modal -->
    <UModal v-model="showEditModal" title="Edit Meeting">
      <UCard>
        <UForm @submit.prevent="updateMeeting">
          <UFormGroup label="Location">
            <UInput v-model="editForm.location" required />
          </UFormGroup>
          <UFormGroup label="Start Hour">
            <UInput v-model="editForm.start_hour" type="datetime-local" required />
          </UFormGroup>
          <UFormGroup label="End Hour">
            <UInput v-model="editForm.end_hour" type="datetime-local" required />
          </UFormGroup>
          <UFormGroup label="Break Time">
            <UInput v-model="editForm.break_time" type="number" required />
          </UFormGroup>
          <UFormGroup label="Admin">
            <USelect v-model="editForm.admin_id" :options="adminOptions" required />
          </UFormGroup>
          <UFormGroup label="Trainer">
            <USelect v-model="editForm.trainer_id" :options="trainerOptions" required />
          </UFormGroup>
          <div class="flex justify-end">
            <UButton type="submit" class="mr-2">Update</UButton>
            <UButton @click="closeEditModal" variant="secondary">Cancel</UButton>
          </div>
        </UForm>
      </UCard>
    </UModal>
  </div>
</template>

<script setup>

const meetingStore = useMeetingStore();
const userStore = useUserStore();

meetingStore.fetchMeetings();
userStore.fetchUsers();

const searchQuery = ref('');
const selectedAdmin = ref(null);
const selectedTrainer = ref(null);

const meetings = computed(() => meetingStore.meetings.map(meeting => ({
  ...meeting,
  adminName: getUserName(meeting.admin_id),
  trainerName: getUserName(meeting.trainer_id),
  modulePromotion: meeting.module.promotion,
  schoolName: meeting.school.name
})));

const admins = computed(() => userStore.users.filter(user => user.roles.some(role => role.name === 'admin')));
const trainers = computed(() => userStore.users.filter(user => user.roles.some(role => role.name === 'trainer')));

const filteredMeetings = computed(() => {
  return meetings.value.filter(meeting => {
    return searchQuery.value
        ? meeting.location.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        meeting.adminName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        meeting.trainerName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        meeting.modulePromotion.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        meeting.schoolName.toLowerCase().includes(searchQuery.value.toLowerCase())
        : true;
  });
});

const showEditModal = ref(false);
const editForm = ref({
  id: 0,
  location: '',
  start_hour: '',
  end_hour: '',
  break_time: 0,
  admin_id: 0,
  trainer_id: 0,
});

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'location', label: 'Location' },
  { key: 'start_hour', label: 'Start Hour', sortable: true },
  { key: 'end_hour', label: 'End Hour', sortable: true },
  { key: 'adminName', label: 'Admin' },
  { key: 'trainerName', label: 'Trainer' },
  { key: 'modulePromotion', label: 'Promotion' },
  { key: 'schoolName', label: 'School' },
  { key: 'actions' }
];

const adminOptions = computed(() => [{ label: 'All Admins', value: null }, ...admins.value.map(admin => ({ label: admin.name, value: admin.id }))]);
const trainerOptions = computed(() => [{ label: 'All Trainers', value: null }, ...trainers.value.map(trainer => ({ label: trainer.name, value: trainer.id }))]);

const getUserName = (id) => {
  const user = userStore.users.find(user => user.id === id);
  return user ? user.name : 'Unknown';
};

const openEditModal = (meeting) => {
  editForm.value = { ...meeting };
  editForm.value.start_hour = new Date(meeting.start_hour).toISOString().slice(0, 16);
  editForm.value.end_hour = new Date(meeting.end_hour).toISOString().slice(0, 16);
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

const updateMeeting = async () => {
  const updatedMeeting = {
    ...editForm.value,
    start_hour: new Date(editForm.value.start_hour).toISOString(),
    end_hour: new Date(editForm.value.end_hour).toISOString(),
  };
  await meetingStore.updateMeeting(updatedMeeting);
  showEditModal.value = false;
};

const actionItems = (row) => [
  [{
    label: 'Edit',
    icon: 'i-heroicons-pencil-square-20-solid',
    click: () => openEditModal(row)
  }], [{
    label: 'Delete',
    icon: 'i-heroicons-trash-20-solid',
    click: () => deleteMeeting(row.id)
  }]
];

const deleteMeeting = async (id) => {
  await meetingStore.deleteMeeting(id);
};
</script>
