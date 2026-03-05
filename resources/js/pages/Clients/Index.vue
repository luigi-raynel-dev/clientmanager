<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/clients';
import { DataPaginator, User, type BreadcrumbItem } from '@/types';
import { Column, DataTableSortEvent } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';
import ListPageHeading from '@/components/ListPageHeading.vue';
import SearchField from '@/components/ui/input/SearchField.vue';
import Actions from '@/components/ui/table/Actions.vue';
import PaginatedTable from '@/components/ui/table/PaginatedTable.vue';
import { Client } from '@/types/client';

export type ClientFilterProps = {
  q?: string
  order_by?: string
  order_direction?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

const props = defineProps<{
  clients: DataPaginator<Client[]>,
  filters?: ClientFilterProps
}>()

const first = ref(0)
watch(() => props.clients.current_page, (page) => {
  first.value = ((Number(page) || 1) - 1) * props.clients.per_page
})

const getClient = (filters: ClientFilterProps) => {
  router.get(index().url, { ...filters }, {
    preserveState: true,
    replace: true,
  })
}

const search = ref(props.filters?.q ?? '')

const onSort = (event: DataTableSortEvent) => {
  const order_by = event.sortField
  if (typeof order_by !== 'string') return
  const order_direction = event.sortOrder === 1 ? 'asc' : 'desc'
  getClient({ ...props.filters, q: search.value, order_by, order_direction, page: 1 })
}

const onPage = (event: any) => {
  const page = event.page + 1
  const per_page = event.rows

  getClient({
    ...props.filters,
    q: search.value,
    page,
    per_page
  })
}

watch(
  search,
  debounce((q) => getClient({ ...props.filters, q, page: 1 }), 400)
)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Client',
    href: index().url,
  },
];

</script>

<template>

  <Head title="Clients" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <ListPageHeading :create-button="{ url: create().url, label: 'Create Client', icon: 'pi pi-user-plus' }">
        <SearchField v-model:search="search" />
      </ListPageHeading>

      <PaginatedTable :per_page="clients.per_page" :total="clients.total" :data="clients.data" :onSort="onSort"
        :onPage="onPage" :first="first">
        <Column sortable field="id" header="ID"></Column>
        <Column sortable field="name" header="Nome"></Column>
        <Column sortable field="email" header="Email"></Column>
        <Column sortable field="created_at" header="Created At">
          <template #body="{ data }">
            {{ new Date(data.created_at).toLocaleDateString() }}
          </template>
        </Column>
        <Column style="flex: 0 0 4rem" header="Actions">
          <template #body="{ data }">
            <Actions :edit="{ url: './' + data.id + '/edit' }" :delete="{ url: './' + data.id, recordId: data.id }" />
          </template>
        </Column>
      </PaginatedTable>
    </div>
  </AppLayout>
</template>
