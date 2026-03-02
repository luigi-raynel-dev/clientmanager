<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Button, useConfirm, useToast } from 'primevue';

const props = defineProps<{
  edit?: {
    url: string
  }
  delete?: {
    url: string
    recordId: number
    successMessage?: string
    errorMessage?: string
  }
}>()

const confirm = useConfirm();
const toast = useToast();

const confirmDeletion = () => {
  if (!props.delete) return

  confirm.require({
    message: `Do you want to delete this record: #${props.delete.recordId}?`,
    header: 'Danger Zone',
    icon: 'pi pi-info-circle',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Delete',
      severity: 'danger'
    },
    accept: () => {
      router.delete(props.delete!.url, {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Success',
            detail: props.delete?.successMessage || 'Record deleted successfully',
            life: 3000,
          })
        },
        onError: () => {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: props.delete?.errorMessage || 'Failed to delete record',
            life: 3000,
          })
        }
      })
    }
  });
};

</script>

<template>
  <div class="flex items-center gap-2">
    <Link v-if="props.edit" :href="props.edit.url" class="flex items-center">
      <Button type="button" severity="secondary" variant="outlined" icon="pi pi-pencil" text size="small" />
    </Link>
    <Button v-if="props.delete" type="button" severity="danger" variant="outlined" icon="pi pi-trash" text size="small"
      @click="() => confirmDeletion()" />
  </div>
</template>