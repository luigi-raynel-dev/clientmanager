<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from 'primevue';

const props = defineProps<{
  createButton?: {
    url: string
    label: string
    icon?: string
  }
}>()

</script>

<template>
  <div class="flex flex-row justify-between items-center">
    <div class="w-full justify-between sm:w-auto flex items-center gap-4">
      <slot />
      <Link v-if="props.createButton" :href="props.createButton.url" class="flex sm:hidden items-center">
        <Button :icon="props.createButton.icon || 'pi pi-plus'" />
      </Link>
    </div>
    <Link v-if="props.createButton" :href="props.createButton.url" class="hidden sm:flex items-center">
      <Button :label="props.createButton.label" :icon="props.createButton.icon || 'pi pi-plus'" />
    </Link>
  </div>
</template>