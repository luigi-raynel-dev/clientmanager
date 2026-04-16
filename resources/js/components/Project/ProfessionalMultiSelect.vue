<script setup lang="ts">
import MultiSelect from 'primevue/multiselect'
import type { User } from '@/types/auth'
import { computed } from 'vue'

const props = defineProps<{
  modelValue: number[]
  professionals: User[]
  label?: string
  placeholder?: string
  error?: string
}>()

const emit = defineEmits(['update:modelValue'])

const selectedValues = computed({
  get: () => props.modelValue ?? [],
  set: (value: number[]) => { }  // emit('update:modelValue', value),
})
</script>

<template>
  <div class="flex flex-col gap-1">
    <label class="font-semibold">{{ label ?? 'Professionals' }}</label>

    <MultiSelect v-model="selectedValues" :options="professionals" optionLabel="name" optionValue="id"
      :placeholder="placeholder ?? 'Search and select professionals'" filter filterPlaceholder="Search professionals"
      display="chip" showClear panelStyle="min-width: 18rem" class="w-full" />

    <small v-if="error" class="text-red-500">{{ error }}</small>
  </div>
</template>
