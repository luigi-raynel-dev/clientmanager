<script setup lang="ts">
import { Button, Drawer, OverlayBadge } from 'primevue';
import { ref } from 'vue';

const props = defineProps<{
  title: string
  clear: () => void
  apply: () => void
  total: number
}>()

const visible = ref(false);

</script>

<template>
  <OverlayBadge v-if="props.total > 0" :value="props.total">
    <Button severity="secondary" icon="pi pi-filter" @click="visible = true" />
  </OverlayBadge>
  <Button v-else severity="secondary" icon="pi pi-filter" @click="visible = true" />
  <Drawer v-model:visible="visible">
    <template #header>
      <h2 class="text-lg font-semibold">{{ props.title }}</h2>
    </template>
    <slot />
    <template #footer>
      <div class="flex items-center gap-2">
        <Button severity="secondary" icon="pi pi-filter-slash" @click="() => {
          props.clear()
          visible = false
        }" />
        <Button label="Apply" class="flex-auto" variant="outlined" @click="() => {
          props.apply()
          visible = false
        }" />
      </div>
    </template>
  </Drawer>
</template>