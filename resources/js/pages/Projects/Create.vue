<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/projects';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';

const props = defineProps<{}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Projects',
    href: index().url,
  },
  {
    title: 'Create Project',
    href: create().url,
  },
];

const toast = useToast();

// Zod schema
const projectSchema = z.object({
  name: z.string().min(3),
  description: z.string().nullable().nullish(),
  base_price: z.number().nullable().nullish(),
  pricing_type_id: z.number().nullable().nullish(),
  estimated_duration_minutes: z.number().nullable().nullish(),
  estimated_duration_type: z.enum(['minutes', 'hours', 'days', 'weeks', 'months']),
  is_active: z.boolean(),
})

const resolver = ref(zodResolver(projectSchema))

// Inertia form
const form = useForm({
  name: '',
  description: '',
})

const submit = () => {
  const result = projectSchema.safeParse(form.data())
  form.clearErrors()

  if (!result.success) {
    const { fieldErrors } = z.flattenError(result.error);

    for (const key in fieldErrors) {
      form.setError(key as any, ((fieldErrors as any)[key]?.[0] ?? ''))
    }

    return
  }

  form.post('/projects',
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

  <Head title="Create Projects" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">

    </div>
  </AppLayout>
</template>
