<script setup lang="ts">
import { IconField, InputIcon, InputText } from 'primevue'


const props = defineProps<{
  search: string
  searchPlaceholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:search', value?: string): void
}>()
</script>

<template>
  <IconField class="w-full sm:w-auto">
    <InputIcon class="pi pi-search" />
    <InputText :modelValue="props.search" @update:modelValue="emit('update:search', $event)"
      :placeholder="props.searchPlaceholder || 'Search...'" class="w-full sm:w-64" />
  </IconField>
</template>