<script setup lang="ts">
import { destroy, edit, status } from '@/routes/services';
import { Service } from '@/types/service';
import { convertFromMinutes } from '@/utils/time';
import { Clock } from 'lucide-vue-next';
import { Button, Card } from 'primevue';
import TextLimiter from '../TextLimiter.vue';
import ToggleStatus from '../ui/toggle/ToggleStatus.vue';
import Actions from '../ui/table/Actions.vue';

const props = defineProps<{
  service: Service
  showAction?: boolean
  showDescription?: boolean
  onSelect?: () => void
  onRemove?: () => void
}>()

</script>

<template>
  <Card :class="`w-full border ${onSelect ? 'cursor-pointer' : ''}`" @click="onSelect">
    <template #title>
      {{ service.name }}
    </template>
    <template #subtitle>
      <div class="flex w-full gap-6">
        <span>
          $ {{ service.base_price || "---" }} / {{
            service.pricing_type?.name || 'fixed'
          }}
        </span>
        <span v-if="service.estimated_duration_minutes" class="flex items-center gap-1">
          <Clock class="size-4" /> {{ convertFromMinutes(service.estimated_duration_minutes,
            service.estimated_duration_type) }} {{ service.estimated_duration_type }}
        </span>
      </div>

    </template>
    <template #content v-if="service.description && showDescription">
      <TextLimiter :text="service.description" :maxLength="500" />
    </template>

    <template #footer v-if="showAction || onRemove">
      <div class="flex items-center justify-end w-full" v-if="onRemove">
        <Button type="button" severity="danger" variant="outlined" icon="pi pi-trash" text size="small"
          @click="onRemove" />
      </div>
      <div class="flex items-center justify-between" v-if="showAction">
        <ToggleStatus variant="switch" :status="Boolean(service.is_active)" :url="status(service.id).url"
          :message="`Do you want to ${service.is_active ? 'deactivate' : 'activate '} this service: #${service.id}?`"
          :header="service.is_active ? 'Deactivate Service' : 'Activate Service'"
          :payload="{ is_active: !service.is_active }"
          :successMessage="`Service ${service.is_active ? 'deactivated' : 'activated'} successfully`"
          errorMessage="Failed to change service status"
          :tooltip="service.is_active ? 'Deactivate Service' : 'Activate Service'" />
        <Actions :edit="{ url: edit(service.id).url }"
          :delete="{ url: destroy(service.id).url, recordId: service.id }" />
      </div>
    </template>
  </Card>
</template>