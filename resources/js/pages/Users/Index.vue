<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, edit, destroy, status } from '@/routes/users';
import { DataPaginator, User, type BreadcrumbItem } from '@/types';
import { Button, Column, DataTable, DataTableSortEvent, IconField, InputIcon, InputText, useConfirm, useToast } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';
import UsersFilter from '@/components/User/UsersFilter.vue';
import { UserFiltersType } from '@/types/user';
import ListPageHeading from '@/components/ListPageHeading.vue';
import SearchField from '@/components/ui/input/SearchField.vue';

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

const confirm = useConfirm();
const toast = useToast();

const confirmDeletion = (user: User) => {
  confirm.require({
    message: `Do you want to delete this user: #${user.id}?`,
    header: 'Danger Zone',
    icon: 'pi pi-info-circle',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Delete',
      severity: 'danger'
    },
    accept: () => {
      router.delete(destroy(user.id).url, {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'User deleted successfully',
            life: 3000,
          })
        },
        onError: () => {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete user',
            life: 3000,
          })
        }
      })
    }
  });
};

const confirmStatusChange = (user: User) => {
  confirm.require({
    message: `Do you want to ${user.is_blocked ? 'unblock' : 'block'} this user: #${user.id}?`,
    header: user.is_blocked ? 'Unblock User' : 'Block User',
    icon: user.is_blocked ? 'pi pi-unlock' : 'pi pi-lock',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: user.is_blocked ? 'Unblock' : 'Block',
      severity: user.is_blocked ? 'success' : 'danger'
    },
    accept: () => {
      router.patch(status(user.id).url, { is_blocked: !user.is_blocked }, {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Success',
            detail: `User ${user.is_blocked ? 'unblocked' : 'blocked'} successfully`,
            life: 3000,
          })
        },
        onError: () => {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to change user status',
            life: 3000,
          })
        }
      })
    }
  });
};

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

      <div class="card">
        <DataTable stripedRows paginator :rows="users.per_page" :totalRecords="users.total" :value="users.data"
          :rowsPerPageOptions="[5, 10, 25, 50]" filterDisplay="menu" lazy @sort="onSort" @page="onPage" :first="first"
          scrollable scrollHeight="70vh">
          <Column sortable field="id" header="ID"></Column>
          <Column sortable field="name" header="Nome"></Column>
          <Column sortable field="email" header="Email"></Column>
          <Column sortable field="role" header="Role"></Column>
          <Column sortable field="is_blocked" header="Status">
            <template #body="{ data }">
              <Button v-tooltip="data.is_blocked ? 'Unblock User' : 'Block User'" type="button"
                :severity="data.is_blocked ? 'danger' : 'success'" variant="outlined"
                :icon="data.is_blocked ? 'pi pi-ban' : 'pi pi-check-circle'" text size="small"
                @click="() => confirmStatusChange(data)" />
            </template>
          </Column>
          <Column sortable field="created_at" header="Created At">
            <template #body="{ data }">
              {{ new Date(data.created_at).toLocaleDateString() }}
            </template>
          </Column>
          <Column style="flex: 0 0 4rem" header="Actions">
            <template #body="{ data }">
              <div class="flex items-center gap-2">
                <Link :href="edit(data.id).url" class="flex items-center">
                  <Button type="button" severity="secondary" variant="outlined" icon="pi pi-pencil" text size="small" />
                </Link>
                <Button type="button" severity="danger" variant="outlined" icon="pi pi-trash" text size="small"
                  @click="() => confirmDeletion(data)" />
              </div>
            </template>
          </Column>
          <template #footer> In total there are {{ users.total }} records.</template>
        </DataTable>
      </div>
    </div>
  </AppLayout>
</template>
