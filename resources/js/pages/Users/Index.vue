<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';

interface User {
  id: number
  name: string
  email: string
  created_at: string
}

const props = defineProps<{
  users: User[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users',
    href: index().url,
  },
];
</script>

<template>

  <Head title="Users" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex justify-end items-center">
        <Link :href="create().url">
          <Button class="bg-primary text-primary-foreground mb-4 cursor-pointer p-2 rounded-sm" variant="secondary">
            Criar usuário
          </Button>
        </Link>
      </div>
      <table class="table-auto w-full border">
        <thead>
          <tr>
            <th class="p-2 text-left">ID</th>
            <th class="p-2 text-left">Nome</th>
            <th class="p-2 text-left">Email</th>
            <th class="p-2 text-left">Criado em</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-t">
            <td class="p-2">{{ user.id }}</td>
            <td class="p-2">{{ user.name }}</td>
            <td class="p-2">{{ user.email }}</td>
            <td class="p-2">
              {{ new Date(user.created_at).toLocaleDateString() }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
