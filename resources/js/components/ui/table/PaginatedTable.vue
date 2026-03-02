<script setup lang="ts">
import { DataTable, DataTablePageEvent, DataTableSortEvent } from 'primevue';

const props = defineProps<{
  data: any[]
  total: number
  per_page: number
  first?: number
  onPage: (event: DataTablePageEvent) => void
  onSort: (event: DataTableSortEvent) => void
}>()


</script>

<template>
  <DataTable stripedRows paginator :rows="props.per_page" :totalRecords="props.total" :value="props.data"
    :rowsPerPageOptions="[5, 10, 25, 50]" lazy @sort="onSort" @page="onPage" :first="props.first" scrollable
    scrollHeight="70vh">
    <slot />
    <template #footer> In total there are {{ props.total }} records.</template>
  </DataTable>
</template>