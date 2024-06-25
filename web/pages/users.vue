<template>
  <div>
    <h1 class="text-center font-black">Users panel </h1>
    <UButton @click="openCreateModal" label="Add New User" class="mb-4" />
    <div>
      <div v-for="user in users" :key="user.id" class="mb-4">
        <UCard>
          <div class="flex justify-between">
            <div class="flex gap-4">
              <h2>{{ user.name }}</h2>
              <p><strong>Email:</strong> {{ user.email }}</p>
              <UBadge v-for="role in user.roles" :key="role.id" :color="role.name === 'admin' ? 'primary' : 'blue'" :label="role.name"/>
            </div>
            <div>
              <UButtonGroup size="sm" orientation="horizontal">
                <UButton @click="openEditModal(user)" label="Edit" icon="i-heroicons-pencil" variant="outline"/>
              </UButtonGroup>
            </div>

          </div>
        </UCard>
      </div>
    </div>

    <!-- Edit Modal -->
    <UModal v-model="showEditModal" title="Edit User">
      <UCard>
        <UForm @submit.prevent="updateUser">
          <UFormGroup label="Name">
            <UInput v-model="editForm.name" required />
          </UFormGroup>
          <UFormGroup label="Email">
            <UInput v-model="editForm.email" type="email" required />
          </UFormGroup>
          <div class="flex justify-end">
            <UButton type="submit" class="mr-2">Update</UButton>
            <UButton @click="closeEditModal" variant="secondary">Cancel</UButton>
          </div>
        </UForm>
      </UCard>
    </UModal>

    <!-- Create Modal -->
    <UModal v-model="showCreateModal" title="Add New User">
      <UCard>
        <UForm @submit.prevent="createUser">
          <UFormGroup label="Name">
            <UInput v-model="createForm.name" required />
          </UFormGroup>
          <UFormGroup label="Email">
            <UInput v-model="createForm.email" type="email" required />
          </UFormGroup>
          <UFormGroup label="Role">
            <USelect v-model="createForm.role" :options="roleOptions" required />
          </UFormGroup>
          <div class="flex justify-end">
            <UButton type="submit" class="mr-2">Create</UButton>
            <UButton @click="closeCreateModal" variant="secondary">Cancel</UButton>
          </div>
        </UForm>
      </UCard>
    </UModal>
  </div>
</template>

<script setup>

definePageMeta({
  middleware: ['role-admin'],
});

const storeUser = useUserStore();

storeUser.fetchUsers();
const users = computed(() => storeUser.users);

const showEditModal = ref(false);
const showCreateModal = ref(false);
const editForm = ref({
  id: 0,
  name: '',
  email: ''
});
const createForm = ref({
  name: '',
  email: '',
  role: ''
});

const roleOptions = ref([
  {label: 'Admin', value: 'admin'},
  {label: 'Trainer', value: 'trainer'}
]);

const openEditModal = (user) => {
  editForm.value = {...user};
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

const openCreateModal = () => {
  createForm.value = {name: '', email: '', role: ''};
  showCreateModal.value = true;
};

const closeCreateModal = () => {
  showCreateModal.value = false;
};

const updateUser = async () => {
  await storeUser.updateUser(editForm.value);
  showEditModal.value = false;
};

const createUser = async () => {
  await storeUser.addUser(createForm.value);
  showCreateModal.value = false;
};
</script>
