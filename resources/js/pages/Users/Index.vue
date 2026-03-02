<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, edit, status, destroy } from '@/routes/users';
import { DataPaginator, User, type BreadcrumbItem } from '@/types';
import { Column, DataTable, DataTableSortEvent } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';
import UsersFilter from '@/components/User/UsersFilter.vue';
import { UserFiltersType } from '@/types/user';
import ListPageHeading from '@/components/ListPageHeading.vue';
import SearchField from '@/components/ui/input/SearchField.vue';
import ToggleStatus from '@/components/ui/toggle/ToggleStatus.vue';
import Actions from '@/components/ui/table/Actions.vue';
import PaginatedTable from '@/components/ui/table/PaginatedTable.vue';

export type UserRoleType = 'admin' | 'user'

export type UsersFilterProps = UserFiltersType & {
  q?: string
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
const form = ref<UserFiltersType>({
  role: props.filters?.role,
  is_blocked: props.filters?.is_blocked
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
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <ListPageHeading :create-button="{ url: create().url, label: 'Create User', icon: 'pi pi-user-plus' }">
        <SearchField v-model:search="search" />
        <UsersFilter :filters="props.filters ?? {}" :apply="(filters) => {
          form = filters
          getUsers({ ...props.filters, ...filters, q: search, page: 1 })
        }" />
      </ListPageHeading>

      <PaginatedTable :per_page="users.per_page" :total="users.total" :data="users.data" :onSort="onSort"
        :onPage="onPage" :first="first">
        <Column sortable field="id" header="ID"></Column>
        <Column sortable field="name" header="Nome"></Column>
        <Column sortable field="email" header="Email"></Column>
        <Column sortable field="role" header="Role"></Column>
        <Column sortable field="is_blocked" header="Status">
          <template #body="{ data }">
            <ToggleStatus :status="!data.is_blocked" :url="status(data.id).url"
              :message="`Do you want to ${data.is_blocked ? 'unblock' : 'block'} this user: #${data.id}?`"
              :header="data.is_blocked ? 'Unblock User' : 'Block User'" :payload="{ is_blocked: !data.is_blocked }"
              :successMessage="`User ${data.is_blocked ? 'unblocked' : 'blocked'} successfully`"
              errorMessage="Failed to change user status" :tooltip="data.is_blocked ? 'Unblock User' : 'Block User'" />
          </template>
        </Column>
        <Column sortable field="created_at" header="Created At">
          <template #body="{ data }">
            {{ new Date(data.created_at).toLocaleDateString() }}
          </template>
        </Column>
        <Column style="flex: 0 0 4rem" header="Actions">
          <template #body="{ data }">
            <Actions :edit="{ url: edit(data.id).url }" :delete="{ url: destroy(data.id).url, recordId: data.id }" />
          </template>
        </Column>
      </PaginatedTable>
    </div>
  </AppLayout>
</template>
