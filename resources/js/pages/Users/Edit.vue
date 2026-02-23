<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create } from '@/routes/users';
import { Form } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { User, type BreadcrumbItem } from '@/types';
import z from 'zod';
import { ref } from 'vue';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { Message, SelectButton, ToggleSwitch, useToast } from 'primevue';

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

        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-2">
            <ToggleSwitch name="active" v-model="form.active" />
            <span :class="form.active ? 'font-bold' : ''">Active</span>
          </div>
          <Message v-if="form.errors.active" severity="error" size="small" variant="simple">{{
            form.errors.active }}</Message>
        </div>

        <div class="flex justify-end my-4">
          <Button label="Cancel" class="p-button-text mr-2" @click="$inertia.visit(index().url)" />
          <Button label="Edit User" icon="pi pi-user-edit" :loading="form.processing" type="submit" />
        </div>
      </Form>
    </div>
  </AppLayout>
</template>
