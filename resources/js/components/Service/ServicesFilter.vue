  <script setup lang="ts">
  import { ServiceFiltersType } from '@/types/service';
  import { Select, SelectButton } from 'primevue';
  import { ref, watch } from 'vue';
  import Filter from '../Filter.vue';
  import { SortOption } from '@/types';

  const props = defineProps<{
    filters: ServiceFiltersType
    apply: (filters: ServiceFiltersType) => void
  }>()

  const form = ref({ ...props.filters, is_active: props.filters.is_active !== undefined ? Number(props.filters.is_active) : undefined })

  const getFilterCount = () => {
    let count = 0
    if (props.filters.is_active !== null && props.filters.is_active !== undefined) count++
    if (props.filters.order_by || props.filters.order_direction) count++
    return count
  }

  type sortOptionsStructure = {
    label: string;
    value: SortOption;
  }

  const sortOptions: sortOptionsStructure[] = [
    { label: 'Oldest', value: { order_by: 'created_at', order_direction: 'asc' } },
    { label: 'Newest', value: { order_by: 'created_at', order_direction: 'desc' } },
    { label: 'Name (A-Z)', value: { order_by: 'name', order_direction: 'asc' } },
    { label: 'Name (Z-A)', value: { order_by: 'name', order_direction: 'desc' } },
    { label: 'Price (Low to High)', value: { order_by: 'base_price', order_direction: 'asc' } },
    { label: 'Price (High to Low)', value: { order_by: 'base_price', order_direction: 'desc' } },
    { label: 'Estimated Hours (Low to High)', value: { order_by: 'estimated_duration_minutes', order_direction: 'asc' } },
    { label: 'Estimated Hours (High to Low)', value: { order_by: 'estimated_duration_minutes', order_direction: 'desc' } },
  ]

  const selectedSort = ref<sortOptionsStructure>()

  watch(selectedSort, (val) => {
    if (val) {
      form.value.order_by = val.value.order_by
      form.value.order_direction = val.value.order_direction
    } else {
      form.value.order_by = undefined
      form.value.order_direction = undefined
    }
  })

</script>

  <template>
    <Filter title="Filter services" :clear="() => {
      selectedSort = undefined
      form = {
        is_active: undefined,
        order_by: undefined,
        order_direction: undefined,
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
        <div class="flex flex-col gap-1">
          <label>Sort By</label>
          <Select v-model="selectedSort" :options="sortOptions" optionLabel="label" placeholder="Select a Sort Option"
            fluid />
        </div>
      </div>
    </Filter>
  </template>