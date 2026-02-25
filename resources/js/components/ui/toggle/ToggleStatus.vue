<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Button, useConfirm, useToast } from 'primevue'

const props = defineProps<{
  status: boolean
  url: string
  message: string
  header: string
  icon?: string
  acceptLabel?: string
  payload: Record<string, any>
  successMessage: string
  errorMessage: string
  tooltip: string
}>()

const confirm = useConfirm();
const toast = useToast();

const confirmStatusChange = () => {
  confirm.require({
    message: props.message,
    header: props.header,
    icon: props.icon || props.status ? 'pi pi-lock' : 'pi pi-unlock',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: props.acceptLabel || "Confirm",
      severity: props.status ? 'danger' : 'success'
    },
    accept: () => {
      router.patch(props.url, props.payload, {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Success',
            detail: props.successMessage,
            life: 3000,
          })
        },
        onError: () => {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: props.errorMessage,
            life: 3000,
          })
        }
      })
    }
  });
};
</script>

<template>
  <Button v-tooltip="tooltip" type="button" :severity="status ? 'success' : 'danger'" variant="outlined"
    :icon="status ? 'pi pi-check-circle' : 'pi pi-ban'" text size="small" @click="confirmStatusChange" />
</template>