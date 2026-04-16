<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/projects';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import ProjectForm, { ProjectFormType } from '@/components/Project/ProjectForm.vue';
import type { User } from '@/types/auth'
import { ProjectStatus } from '@/types/project';
import { Client } from '@/types/client';

const props = defineProps<{
  name: string;
  statuses: ProjectStatus[]
  professionals: User[]
  clients: Client[]
}>()

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
  priority: z.enum(['Low', 'Medium', 'High']).default('Medium'),
  professional_ids: z.array(z.number()),
})

const resolver = ref(zodResolver(projectSchema))

// Inertia form
const form = useForm({
  name: props.name,
  description: '',
  priority: 'Medium',
  status_id: props.statuses.find(({ is_default }) => is_default)?.id ?? null,
  start_date: null,
  end_date: null,
  professional_ids: [],
  client_ids: [],
} as ProjectFormType)

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
    <div class="w-full flex h-full flex-1 flex-col p-4 my-6">
      <ProjectForm :form="form" :statuses="statuses" :professionals="props.professionals" :clients="props.clients"
        :resolver="resolver" submit-label="Create Service" @submit="submit" @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
