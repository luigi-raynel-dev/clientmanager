<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/clients';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import ClientForm from '@/components/Client/ClientForm.vue';
import { Client } from '@/types/client';

const props = defineProps<{
  client: Client
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Clients',
    href: index().url,
  },
  {
    title: 'Edit Client',
    href: create().url,
  },
];

const toast = useToast();

// Zod schema
const clientSchema = z.object({
  name: z.string().min(3),
  email: z.email(),
})

const resolver = ref(zodResolver(clientSchema))


// Inertia form
const form = useForm({ ...props.client })

const submit = () => {
  const result = clientSchema.safeParse(form.data())
  form.clearErrors()

  if (!result.success) {
    const { fieldErrors } = z.flattenError(result.error);

    for (const key in fieldErrors) {
      form.setError(key as any, ((fieldErrors as any)[key]?.[0] ?? ''))
    }

    return
  }

  form.put(`/clients/${props.client.id}`,
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'Client updated successfully',
          life: 3000,
        })
      }
    }
  )
}
</script>

<template>

  <Head title="Edit Client" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">
      <ClientForm :form="form" :resolver="resolver" submit-label="Update Client" submit-icon="pi pi-user-edit"
        @submit="submit" @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
