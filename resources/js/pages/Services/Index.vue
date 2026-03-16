<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/services';
import { DataPaginator, type BreadcrumbItem } from '@/types';
import { Card, Paginator } from 'primevue';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';
import ListPageHeading from '@/components/ListPageHeading.vue';
import SearchField from '@/components/ui/input/SearchField.vue';
import { Service, ServiceFiltersType } from '@/types/service';
import { Clock } from 'lucide-vue-next';
import ServicesFilter from '@/components/Service/ServicesFilter.vue';

export type ServiceFilterProps = {
  q?: string
  is_active?: boolean
  order_by?: string
  order_direction?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

const props = defineProps<{
  services: DataPaginator<Service[]>,
  filters?: ServiceFilterProps
}>()

const first = ref(0)
watch(() => props.services.current_page, (page) => {
  first.value = ((Number(page) || 1) - 1) * props.services.per_page
})

const getServices = (filters: ServiceFilterProps) => {
  router.get(index().url, { ...filters }, {
    preserveState: true,
    replace: true,
  })
}

const search = ref(props.filters?.q ?? '')
const form = ref<ServiceFiltersType>({
  is_active: props.filters?.is_active,
  order_by: props.filters?.order_by,
  order_direction: props.filters?.order_direction,
  per_page: props.filters?.per_page,
})


watch(
  search,
  debounce((q) => getServices({ ...props.filters, q, page: 1 }), 400)
)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Service',
    href: index().url,
  },
];

const onPageChange = (event: any) => {
  const page = event.page + 1

  getServices({
    ...props.filters,
    page
  })
}

</script>

<template>

  <Head title="Services" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <ListPageHeading :create-button="{ url: create().url, label: 'Create Service' }">
        <SearchField v-model:search="search" />
        <ServicesFilter :filters="props.filters ?? {}" :apply="(filters) => {
          form = filters
          getServices({ ...props.filters, ...filters, q: search, page: 1 })
        }" />
      </ListPageHeading>

      <div class="flex flex-col gap-6">
        <p v-if="props.services.total === 0" class="text-center text-neutral-500">
          No services found.
        </p>
        <p v-else class="text-neutral-500">
          There are {{ props.services.total }} services.
        </p>
        <Card v-for="service in props.services.data" :key="service.id" class="w-full">
          <template #title>
            <div class="w-full">
              {{ service.name }}
            </div>
          </template>
          <template #subtitle>
            <div class="flex w-full gap-6">
              <span>
                $ {{ service.base_price || "---" }} / {{
                  service.other_price_type || service.price_type || 'fixed'
                }}
              </span>
              <span v-if="Boolean(service.estimated_duration_hours)" class="flex items-center gap-1">
                <Clock class="size-4" /> {{ service.estimated_duration_hours }} hours
              </span>
            </div>

          </template>
          <template #content>
            <p class="m-0">
              {{ service.description }}
            </p>
          </template>
        </Card>

        <Paginator :rows="props.services.per_page" :totalRecords="props.services.total" :first="first"
          @page="onPageChange" />
      </div>
    </div>
  </AppLayout>
</template>
