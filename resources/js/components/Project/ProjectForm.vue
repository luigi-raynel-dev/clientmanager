<script setup lang="ts">
import { Form, FormProps } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { InertiaForm } from '@inertiajs/vue3'
import { Card, Textarea } from 'primevue';
import OptionalField from '../ui/label/OptionalField.vue';

export type ProjectFormType = {
  name: string;
  description?: string;
};

const props = defineProps<{
  form: InertiaForm<ProjectFormType>
  resolver: FormProps["resolver"]
  submitLabel: string
}>()

defineEmits(['submit', 'cancel'])

</script>

<template>
  <Form :resolver="resolver" @submit="$emit('submit')" class="flex flex-col md:flex-row gap-16 w-full">

    <div class="w-full md:w-2/3">
      <h2 class=" text-2xl font-bold mb-2">Project Details</h2>
      <p class="text-gray-600 mb-4">Provide the basic information about the project.</p>
      <div class="flex flex-col gap-4 w-full">
        <div class="flex flex-col gap-1">
          <label>Name</label>
          <InputText :invalid="Boolean(form.errors.name)" v-model="form.name" />
          <small class="text-red-500">{{ form.errors.name }}</small>
        </div>

        <div class="flex flex-col gap-1">
          <label>Description
            <OptionalField />
          </label>
          <Textarea :invalid="Boolean(form.errors.description)" v-model="form.description" rows="5" cols="30" />
          <small class="text-red-500">{{ form.errors.description }}</small>
        </div>

        <Card>
          <template #title>Services</template>
          <template #content>
            <div class="flex gap-4 flex-col lg:flex-row lg:gap-2 w-full">
              <Button icon="pi pi-plus" iconPos="right" label="Add Service" class="p-button-sm" severity="info" />
            </div>
          </template>
        </Card>

      </div>

      <!-- Actions -->
      <div class="flex justify-end my-4">
        <Button label="Cancel" class="p-button-text mr-2" @click="$emit('cancel')" />
        <Button :label="submitLabel" :loading="form.processing" type="submit" />
      </div>
    </div>

    <div class="w-full md:w-1/3 md:self-start md:sticky md:top-4">
      <Card class="h-max border">
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="flex flex-col">
              <h2 class="text-xl font-bold mb-2">Clients</h2>
              <p class="text-gray-600 mb-4">You can assign clients to this project after creating it.</p>
            </div>
            <div class="flex flex-col">
              <h2 class="text-xl font-bold mb-2">Professionals</h2>
              <p class="text-gray-600 mb-4">You can assign professionals to this project after creating it.</p>
            </div>
          </div>
        </template>
      </Card>
    </div>

  </Form>
</template>