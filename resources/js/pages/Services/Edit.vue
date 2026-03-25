<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/services';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import ServiceForm from '@/components/Service/ServiceForm.vue';
import { Service } from '@/types/service';
import { convertFromMinutes, convertToMinutes } from '@/utils/time';

const props = defineProps<{
  service: Service
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Services',
    href: index().url,
  },
  {
    title: 'Edit Service',
    href: create().url,
  },
];

const toast = useToast();

// Zod schema
const serviceSchema = z.object({
  name: z.string().min(3),
  description: z.string().nullable().nullish(),
  base_price: z.number().nullable().nullish(),
  pricing_type_id: z.number().nullable().nullish(),
  estimated_duration_minutes: z.number().nullable().nullish(),
  estimated_duration_type: z.enum(['minutes', 'hours', 'days', 'weeks', 'months']),
  is_active: z.boolean(),
})

const resolver = ref(zodResolver(serviceSchema))

console.log(props.service)

// Inertia form
const form = useForm({
  ...props.service,
  base_price: Number(props.service.base_price),
  estimated_duration_minutes: convertFromMinutes(Number(props.service.estimated_duration_minutes), props.service.estimated_duration_type),
  is_active: Boolean(props.service.is_active),
})

const submit = () => {
  const result = serviceSchema.safeParse(form.data())
  form.clearErrors()

  if (!result.success) {
    const { fieldErrors } = z.flattenError(result.error);

    for (const key in fieldErrors) {
      form.setError(key as any, ((fieldErrors as any)[key]?.[0] ?? ''))
    }

    return
  }

  if (form.estimated_duration_minutes) {
    form.estimated_duration_minutes = convertToMinutes(form.estimated_duration_minutes, form.estimated_duration_type)
  }

  form.put(`/services/${props.service.id}`,
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'Service updated successfully',
          life: 3000,
        })
      }
    }
  )
}
</script>

<template>

  <Head title="Edit Service" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">
      <ServiceForm :form="form" :resolver="resolver" submit-label="Update Service" submit-icon="pi pi-user-edit"
        @submit="submit" @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
