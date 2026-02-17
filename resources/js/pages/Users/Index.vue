<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, edit } from '@/routes/users';
import { DataPaginator, User, type BreadcrumbItem } from '@/types';
import { Button, Column, DataTable, DataTableSortEvent, Drawer, IconField, InputIcon, InputText, OverlayBadge, SelectButton } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';

export type UserRoleType = 'admin' | 'user'

export type UsersFilterProps = {
  q?: string
  role?: UserRoleType
  order_by?: string
  order_direction?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

const props = defineProps<{
  users: DataPaginator<User[]>,
  filters?: UsersFilterProps
}>()

const first = ref(0)
watch(() => props.users.current_page, (page) => {
  first.value = ((Number(page) || 1) - 1) * props.users.per_page
})

const getUsers = (filters: UsersFilterProps) => {
  router.get(index().url, { ...filters }, {
    preserveState: true,
    replace: true,
  })
}

const search = ref(props.filters?.q ?? '')
const form = ref({
  role: props.filters?.role
})

const onSort = (event: DataTableSortEvent) => {
  const order_by = event.sortField
  if (typeof order_by !== 'string') return
  const order_direction = event.sortOrder === 1 ? 'asc' : 'desc'
  getUsers({ ...props.filters, ...form.value, q: search.value, order_by, order_direction, page: 1 })
}

const onPage = (event: any) => {
  const page = event.page + 1
  const per_page = event.rows

  getUsers({
    ...props.filters,
    ...form.value,
    q: search.value,
    page,
    per_page
  })
}

watch(
  search,
  debounce((q) => getUsers({ ...props.filters, ...form.value, q, page: 1 }), 400)
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
          <OverlayBadge v-if="form.role !== undefined" :value="form.role !== undefined ? 1 : 0">
            <Button severity="secondary" icon="pi pi-filter" @click="visible = true" />
          </OverlayBadge>
          <Button v-else severity="secondary" icon="pi pi-filter" @click="visible = true" />
        </div>
        <Link :href="create().url" class="flex items-center">
          <Button label="Create User" icon="pi pi-user-plus" />
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
            <Button severity="secondary" icon="pi pi-filter-slash" @click="() => {
              form.role = undefined
              getUsers({ ...props.filters, ...form, q: search, page: 1 })
              visible = false
            }" />
            <Button label="Apply" class="flex-auto" variant="outlined" @click="() => {
              getUsers({ ...props.filters, ...form, q: search, page: 1 })
              visible = false
            }" />
          </div>
        </template>
      </Drawer>
      <div class="card">
        <DataTable stripedRows paginator :rows="users.per_page" :totalRecords="users.total" :value="users.data"
          :rowsPerPageOptions="[5, 10, 25, 50]" filterDisplay="menu" lazy @sort="onSort" @page="onPage" :first="first"
          scrollable scrollHeight="70vh">
          <Column sortable field="id" header="ID"></Column>
          <Column sortable field="name" header="Nome"></Column>
          <Column sortable field="email" header="Email"></Column>
          <Column sortable field="role" header="Role"></Column>
          <Column sortable field="created_at" header="Created At">
            <template #body="{ data }">
              {{ new Date(data.created_at).toLocaleDateString() }}
            </template>
          </Column>
          <Column style="flex: 0 0 4rem" header="Actions">
            <template #body="{ data }">
              <Link :href="edit(data.id).url" class="flex items-center">
                <Button type="button" severity="secondary" variant="outlined" icon="pi pi-pencil" text size="small" />
              </Link>
            </template>
          </Column>
          <template #footer> In total there are {{ users.total }} records.</template>
        </DataTable>
      </div>
    </div>
  </AppLayout>
</template>
