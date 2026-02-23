<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { User, type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { useToast } from 'primevue';
import UserForm from '@/components/User/UserForm.vue';

const props = defineProps<{
  user: User
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users',
    href: index().url,
  },
  {
    title: 'Edit User',
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
})

const resolver = ref(zodResolver(userSchema))


// Inertia form
const form = useForm({ ...props.user, active: !props.user.is_blocked })

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

  form.put(`/users/${props.user.id}`,
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: 'User updated successfully',
          life: 3000,
        })
      }
    }
  )
}
</script>

<template>

  <Head title="Edit User" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 my-6">
      <UserForm :form="form" :resolver="resolver" submit-label="Update User" submit-icon="pi pi-user-edit"
        @submit="submit" @cancel="$inertia.visit(index().url)" />
    </div>
  </AppLayout>
</template>
