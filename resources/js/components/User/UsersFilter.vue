<script setup lang="ts">
import { UserFiltersType } from '@/types/user';
import { SelectButton } from 'primevue';
import { ref } from 'vue';
import Filter from '../Filter.vue';

const props = defineProps<{
  filters: UserFiltersType
  apply: (filters: UserFiltersType) => void
}>()

const form = ref({ ...props.filters })

const getFilterCount = () => {
  let count = 0
  if (props.filters.role) count++
  if (props.filters.is_blocked !== null && props.filters.is_blocked !== undefined) count++
  return count
}

</script>

<template>
  <Filter title="Filter users" :clear="() => {
    form = {
      role: undefined,
      is_blocked: undefined,
    }
    props.apply(form)
  }" :apply="() => props.apply(form)" :total="getFilterCount()">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <label>Role</label>
        <div class="card flex justify-center">
          <SelectButton fluid v-model="form.role" :options="[
            { label: 'Admin', value: 'admin' },
            { label: 'User', value: 'user' },
          ]" optionLabel="label" optionValue="value" />
        </div>
      </div>
      <div class="flex flex-col gap-1">
        <label>Status</label>
        <div class="card flex justify-center">
          <SelectButton fluid v-model="form.is_blocked" :options="[
            { label: 'Blocked', value: 1 },
            { label: 'Active', value: 0 },
          ]" optionLabel="label" optionValue="value" />
        </div>
      </div>
    </div>
  </Filter>
</template>