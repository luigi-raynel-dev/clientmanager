<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import { Button, Column, DataTable } from 'primevue';

interface User {
  id: number
  name: string
  email: string
  created_at: string
  role: 'admin' | 'user'
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
      <div class="card">
        <DataTable :value="users" tableStyle="min-width: 50rem">
          <Column field="id" header="ID"></Column>
          <Column field="name" header="Nome"></Column>
          <Column field="email" header="Email"></Column>
          <Column field="role" header="Role"></Column>
          <Column header="Created At">
            <template #body="{ data }">
              {{ new Date(data.created_at).toLocaleDateString() }}
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </AppLayout>
</template>
