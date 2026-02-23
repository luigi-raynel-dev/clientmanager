<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import UserForm from '@/components/User/UserForm.vue';

defineProps<{}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users',
    href: index().url,
  },
  {
    title: 'Create User',
    href: create().url,
  },
];

const toast = useToast();

// Zod schema
const userSchema = z.object({
  name: z.string().min(3),
  email: z.email(),
  role: z.enum(['admin', 'user']),
  active: z.boolean(),
  password: z.string().min(8).regex(/[A-Z]/, 'Must contain an uppercase letter').regex(/[a-z]/, 'Must contain a lowercase letter').regex(/[0-9]/, 'Must contain a number'),
  password_confirmation: z.string().min(8),
})
  .refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match',
    path: ['password_confirmation'],
  })

const resolver = ref(zodResolver(userSchema))


// Inertia form
const form = useForm({
  name: '',
  email: '',
  role: 'user',
  active: true,
  is_blocked: false,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  const result = userSchema.safeParse(form.data())
  form.clearErrors()

  if (!result.success) {
    const { fieldErrors } = z.flattenError(result.error);

    for (const key in fieldErrors) {
      form.setError(key as any, ((fieldErrors as any)[key]?.[0] ?? ''))
    }

    return
  }

  form.is_blocked = !form.active

  form.post('/users',
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'User created successfully',
          life: 3000,
        })
      }
    }
  )
}
</script>

<template>

  <Head title="Create Users" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">
      <UserForm :form="form" :resolver="resolver" showPassword submit-label="Create User" submit-icon="pi pi-user-plus"
        @submit="submit" @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
