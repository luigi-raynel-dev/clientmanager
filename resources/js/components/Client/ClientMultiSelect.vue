<script setup lang="ts">
import MultiSelect from 'primevue/multiselect'
import { computed } from 'vue'
import { Client } from '@/types/client';

const props = defineProps<{
  modelValue: number[]
  clients: Client[]
  label?: string
  placeholder?: string
  error?: string
}>()

const emit = defineEmits(['update:modelValue'])

const selectedValues = computed({
  get: () => props.modelValue ?? [],
  set: (value: number[]) => emit('update:modelValue', value),
})
</script>

<template>
  <div class="flex flex-col">
    <MultiSelect v-model="selectedValues" :options="clients" optionLabel="name" optionValue="id"
      :placeholder="placeholder ?? 'Search and select clients'" filter filterPlaceholder="Search clients" display="chip"
      showClear panelStyle="min-width: 18rem" class="w-full" />

    <small v-if="error" class="text-red-500">{{ error }}</small>
  </div>
</template>
