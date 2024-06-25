import { defineStore } from 'pinia';
import { useFetch, useRuntimeConfig, useNuxtApp } from '#app';

export type Role = {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
};

export type Admin = {
    id: number;
    ulid: string;
    name: string;
    email: string;
    avatar: string | null;
    has_password: boolean;
    roles: Role[];
};

export const useAdminStore = defineStore('admin', () => {
    const config = useRuntimeConfig();
    const nuxtApp = useNuxtApp();

    const admins = ref<Admin[]>([]);
    const loading = ref(false);

    const fetchAdmins = async () => {
        loading.value = true;

        const { refresh } = useFetch<{ users: Admin[] }>('/users?role=admin', {
            method: 'GET',
            immediate: true,
            onResponse({ response }) {
                if (response.status === 200) {
                    admins.value = response._data.users;
                }
                loading.value = false;
            }
        });

        refresh();
    };

    const addAdmin = async (admin: { name: string; email: string }) => {
        loading.value = true;

        const { data } = await useFetch<{ user: Admin }>('/users', {
            method: 'POST',
            body: JSON.stringify({ ...admin, role: 'admin' }),
            headers: {
                'Content-Type': 'application/json',
            },
        });

        admins.value.push(data.value.user);
        loading.value = false;
    };

    const updateAdmin = async (admin: { id: number; name: string; email: string }) => {
        loading.value = true;

        await useFetch(`/users/${admin.id}`, {
            method: 'PUT',
            body: JSON.stringify(admin),
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const index = admins.value.findIndex(a => a.id === admin.id);
        if (index !== -1) {
            admins.value[index] = { ...admins.value[index], ...admin };
        }
        loading.value = false;
    };

    const deleteAdmin = async (id: number) => {
        loading.value = true;

        await useFetch(`/users/${id}`, {
            method: 'DELETE',
        });

        admins.value = admins.value.filter(a => a.id !== id);
        loading.value = false;
    };

    return { admins, loading, fetchAdmins, addAdmin, updateAdmin, deleteAdmin };
});
