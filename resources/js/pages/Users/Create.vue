<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';

interface User {
  id: number
  name: string
  email: string
  created_at: string

}

defineProps<{}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users',
    href: index().url,
  },
  {
    title: 'Create User',
    href: create().url,
  },
];
</script>

<template>

  <Head title="Create Users" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

    </div>
  </AppLayout>
</template>
