<script setup lang="ts">
import { Form, FormProps } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { InertiaForm } from '@inertiajs/vue3'

export type ClientFormType = {
  name: string;
  email: string;
};

defineProps<{
  form: InertiaForm<ClientFormType>
  resolver: FormProps["resolver"]
  submitLabel: string
  submitIcon: string
}>()

defineEmits(['submit', 'cancel'])
</script>

<template>
  <Form :resolver="resolver" @submit="$emit('submit')" class="flex flex-col gap-4 max-w-xl w-full mx-auto">

    <!-- Name -->
    <div class="flex flex-col gap-1">
      <label>Name</label>
      <InputText :invalid="Boolean(form.errors.name)" v-model="form.name" />
      <small class="text-red-500">{{ form.errors.name }}</small>
    </div>

    <!-- Email -->
    <div class="flex flex-col gap-1">
      <label>Email</label>
      <InputText :invalid="Boolean(form.errors.email)" v-model="form.email" />
      <small class="text-red-500">{{ form.errors.email }}</small>
    </div>

    <!-- Actions -->
    <div class="flex justify-end my-4">
      <Button label="Cancel" class="p-button-text mr-2" @click="$emit('cancel')" />
      <Button :label="submitLabel" :icon="submitIcon" :loading="form.processing" type="submit" />
    </div>

  </Form>
</template>