import { defineStore } from 'pinia';
import { useFetch, useRuntimeConfig, useNuxtApp } from '#app';

export type Role = {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
};

export type User = {
    id: number;
    ulid: string;
    name: string;
    email: string;
    avatar: string | null;
    has_password: boolean;
    roles: Role[];
};

export const useUserStore = defineStore('user', () => {
    const config = useRuntimeConfig();
    const nuxtApp = useNuxtApp();

    const users = ref<User[]>([]);
    const loading = ref(false);

    const fetchUsers = async () => {
        loading.value = true;

        const { refresh } = useFetch<{ users: User[] }>('/users', {
            method: 'GET',
            immediate: true,
            onResponse({ response }) {
                if (response.status === 200) {
                    users.value = response._data.users;
                }
                loading.value = false;
            }
        });

        refresh();
    };

    const addUser = async (user: { name: string; email: string; role: string }) => {
        loading.value = true;

        const { data } = await useFetch<{ user: User }>('/users', {
            method: 'POST',
            body: JSON.stringify(user),
            headers: {
                'Content-Type': 'application/json',
            },
        });

        users.value.push(data.value.user);
        loading.value = false;
    };

    const updateUser = async (user: { id: number; name: string; email: string }) => {
        loading.value = true;

        await useFetch(`/users/${user.id}`, {
            method: 'PUT',
            body: JSON.stringify(user),
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const index = users.value.findIndex(u => u.id === user.id);
        if (index !== -1) {
            users.value[index] = { ...users.value[index], ...user };
        }
        loading.value = false;
    };

    const deleteUser = async (id: number) => {
        loading.value = true;

        await useFetch(`/users/${id}`, {
            method: 'DELETE',
        });

        users.value = users.value.filter(u => u.id !== id);
        loading.value = false;
    };

    return { users, loading, fetchUsers, addUser, updateUser, deleteUser };
});
