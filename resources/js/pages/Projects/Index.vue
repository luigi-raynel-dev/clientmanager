<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/projects';
import { DataPaginator, type BreadcrumbItem } from '@/types';
import debounce from 'lodash.debounce'
import { ref, watch } from 'vue';
import ListPageHeading from '@/components/ListPageHeading.vue';
import SearchField from '@/components/ui/input/SearchField.vue';
import { Card } from 'primevue';
import { Project } from '@/types/project';

export type ProjectFilterProps = {
  q?: string
  is_active?: number
  order_by?: string
  order_direction?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

const props = defineProps<{
  projects: DataPaginator<Project[]>,
  filters?: ProjectFilterProps
}>()

const first = ref(0)
watch(() => props.projects.current_page, (page) => {
  first.value = ((Number(page) || 1) - 1) * props.projects.per_page
})

const getProjects = (filters: ProjectFilterProps) => {
  router.get(index().url, { ...filters }, {
    preserveState: true,
    replace: true,
  })
}

const search = ref(props.filters?.q ?? '')

watch(
  search,
  debounce((q) => getProjects({ ...props.filters, q, page: 1 }), 400)
)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Project',
    href: index().url,
  },
];

const onPageChange = (event: any) => {
  const page = event.page + 1
  const per_page = event.rows

  getProjects({
    ...props.filters,
    page,
    per_page
  })
}

</script>

<template>

  <Head title="Projects" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <ListPageHeading :create-button="{ url: create().url, label: 'Create Project' }">
        <SearchField v-model:search="search" />
      </ListPageHeading>

      <div class="flex flex-col gap-6">
        <p v-if="props.projects.total === 0" class="text-center text-neutral-500">
          No projects found.
        </p>
        <p v-else class="text-neutral-500">
          There are {{ props.projects.total }} projects.
        </p>
        <Card v-for="project in props.projects.data" :key="project.id" class="w-full">
          <template #title>
            {{ project.name }}
          </template>
          <template #content v-if="project.description">
            <TextLimiter :text="project.description" :maxLength="500" />
          </template>
        </Card>

        <Paginator :rows="props.projects.per_page" :totalRecords="props.projects.total" :first="first"
          @page="onPageChange" :rowsPerPageOptions="[5, 10, 25, 50]" />
      </div>
    </div>
  </AppLayout>
</template>
