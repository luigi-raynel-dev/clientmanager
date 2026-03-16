<script setup lang="ts">
import { ServiceFiltersType } from '@/types/service';
import { SelectButton } from 'primevue';
import { ref } from 'vue';
import Filter from '../Filter.vue';

const props = defineProps<{
  filters: ServiceFiltersType
  apply: (filters: ServiceFiltersType) => void
}>()

const form = ref({ ...props.filters })

const getFilterCount = () => {
  let count = 0
  if (props.filters.is_active !== null && props.filters.is_active !== undefined) count++
  return count
}

</script>

<template>
  <Filter title="Filter services" :clear="() => {
    form = {
      is_active: undefined,
    }
    props.apply(form)
  }" :apply="() => props.apply(form)" :total="getFilterCount()">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <label>Status</label>
        <div class="card flex justify-center">
          <SelectButton fluid v-model="form.is_active" :options="[
            { label: 'Active', value: 1 },
            { label: 'Inactive', value: 0 },
          ]" optionLabel="label" optionValue="value" />
        </div>
      </div>
    </div>
  </Filter>
</template>