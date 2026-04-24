<script setup lang="ts">
import { Service } from '@/types/service';
import { Dialog } from 'primevue'
import ServiceCard from './ServiceCard.vue';

const props = defineProps<{
  visible: boolean
  services: Service[]
  selectedServices: Service[]
}>()

const emit = defineEmits(['update:visible', 'update:selectedServices'])

const close = () => {
  emit('update:visible', false)
}

const add = (service: Service) => {
  emit('update:selectedServices', [...props.selectedServices, service])
  close()
}

</script>

<template>
  <Dialog :visible="props.visible" @update:visible="close" modal header="Add services" :style="{ width: '50rem' }">
    <div class="flex flex-col gap-6">
      <ServiceCard v-for="service in props.services" :key="service.id" :service="service"
        v-on:select="() => add(service)" />
    </div>
  </Dialog>
</template>