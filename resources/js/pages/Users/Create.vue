<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { Form } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import { type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { SelectButton, useToast } from 'primevue';

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
      <Form :resolver="resolver" @submit="submit" class="flex flex-col gap-4 max-w-xl w-full justify-center mx-auto">

        <div class="flex flex-col gap-1">
          <label>Name</label>
          <InputText :invalid="Boolean(form.errors.name)" v-model="form.name" />
          <small class="text-red-500">{{ form.errors.name }}</small>
        </div>


        <div class="flex flex-col gap-1">
          <label>Email</label>
          <InputText :invalid="Boolean(form.errors.email)" v-model="form.email" />
          <small class="text-red-500">{{ form.errors.email }}</small>
        </div>

        <div class="flex flex-col gap-1">
          <label>Role</label>
          <div class="card flex justify-center">
            <SelectButton :invalid="Boolean(form.errors.role)" fluid v-model="form.role" :options="[
              { label: 'Admin', value: 'admin' },
              { label: 'User', value: 'user' },
            ]" optionLabel="label" optionValue="value" />
          </div>
          <small class="text-red-500">{{ form.errors.role }}</small>
        </div>

        <div class="flex flex-col gap-1">
          <label>Password</label>
          <Password :invalid="Boolean(form.errors.password)" v-model="form.password" fluid toggleMask />
          <small class="text-red-500">{{ form.errors.password }}</small>
        </div>

        <div class="flex flex-col gap-1">
          <label>Confirm Password</label>
          <Password :invalid="Boolean(form.errors.password_confirmation)" v-model="form.password_confirmation" fluid
            toggle-mask :feedback="false" />
          <small class="text-red-500">{{ form.errors.password_confirmation }}</small>
        </div>

        <div class="flex justify-end my-4">
          <Button label="Cancel" class="p-button-text mr-2" @click="$inertia.visit(index().url)" />
          <Button label="Create User" icon="pi pi-user-plus" :loading="form.processing" type="submit" />
        </div>
      </Form>
    </div>
  </AppLayout>
</template>
