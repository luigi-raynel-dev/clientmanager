<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import { Button, Column, DataTable, DataTableSortEvent, Drawer, IconField, InputIcon, InputText, SelectButton } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';

export type UserRoleType = 'admin' | 'user'

export interface User {
  id: number
  name: string
  email: string
  created_at: string
  role: UserRoleType
}

export type UsersFilterProps = {
  q?: string
  role?: UserRoleType
  order_by?: string
  order_direction?: 'asc' | 'desc'
}

const props = defineProps<{
  users: User[],
  filters?: UsersFilterProps
}>()

const getUsers = (filters: UsersFilterProps) => {
  router.get(index().url, { ...filters }, {
    preserveState: true,
    replace: true,
  })
}

const onSort = (event: DataTableSortEvent) => {
  const order_by = event.sortField
  if (typeof order_by !== 'string') return
  const order_direction = event.sortOrder === 1 ? 'asc' : 'desc'
  getUsers({ ...form.value, q: search.value, order_by, order_direction })
}

const search = ref(props.filters?.q ?? '')
const form = ref({
  role: props.filters?.role
})

watch(
  search,
  debounce((q) => getUsers({ ...form.value, q }), 400)
)

const visible = ref(false);

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
      <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
          <IconField>
            <InputIcon class="pi pi-search" />
            <InputText v-model="search" placeholder="Search users..." class="w-64" />
          </IconField>
          <Button severity="secondary" icon="pi pi-filter" @click="visible = true" />
        </div>
        <Link :href="create().url" class="flex items-center">
          <Button class="bg-primary text-primary-foreground cursor-pointer p-2 rounded-sm" variant="secondary">
            Create User
          </Button>
        </Link>
      </div>
      <Drawer v-model:visible="visible">
        <template #header>
          <h2 class="text-lg font-semibold">Filter users</h2>
        </template>
        <div class="flex flex-col gap-1">
          <label>Role</label>
          <div class="card flex justify-center">
            <SelectButton fluid v-model="form.role" :options="[
              { label: 'Admin', value: 'admin' },
              { label: 'User', value: 'user' },
            ]" optionLabel="label" optionValue="value" />
          </div>
        </div>
        <template #footer>
          <div class="flex items-center gap-2">
            <Button label="Apply" class="flex-auto" variant="outlined" @click="() => {
              getUsers({ ...form, q: search })
              visible = false
            }" />
          </div>
        </template>
      </Drawer>
      <div class="card">
        <DataTable stripedRows :value="users" filterDisplay="menu" lazy @sort="onSort" tableStyle="min-width: 50rem">
          <Column sortable field="id" header="ID"></Column>
          <Column sortable field="name" header="Nome"></Column>
          <Column sortable field="email" header="Email"></Column>
          <Column sortable field="role" header="Role"></Column>
          <Column sortable field="created_at" header="Created At">
            <template #body="{ data }">
              {{ new Date(data.created_at).toLocaleDateString() }}
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </AppLayout>
</template>
