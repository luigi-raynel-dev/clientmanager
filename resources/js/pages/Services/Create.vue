<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/services';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import ServiceForm, { ServiceFormType } from '@/components/Service/ServiceForm.vue';
import { convertToMinutes } from '@/utils/time';

defineProps<{}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Services',
    href: index().url,
  },
  {
    title: 'Create Service',
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

// Inertia form
const form = useForm({
  name: '',
  description: '',
  pricing_type_id: null,
  base_price: undefined,
  estimated_duration_minutes: undefined,
  estimated_duration_type: 'hours',
  is_active: true,
} as ServiceFormType)

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

  form.post('/services',
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'Service created successfully',
          life: 3000,
        })
      }
    }
  )
}
</script>

<template>

  <Head title="Create Services" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">
      <ServiceForm :form="form" :resolver="resolver" showPassword submit-label="Create Service" @submit="submit"
        @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
