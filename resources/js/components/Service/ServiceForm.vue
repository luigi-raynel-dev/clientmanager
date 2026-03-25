<script setup lang="ts">
import { Form, FormProps } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { InertiaForm } from '@inertiajs/vue3'
import { AutoComplete, Card, IconField, InputIcon, InputNumber, Message, Select, Textarea, ToggleSwitch } from 'primevue';
import { ref } from 'vue'
import OptionalField from '../ui/label/OptionalField.vue';

export type ServiceFormType = {
  name: string;
  description?: string;
  base_price?: number;
  pricing_type_id?: number | null;
  estimated_duration_minutes?: number;
  estimated_duration_type: 'minutes' | 'hours' | 'days' | 'weeks' | 'months';
  is_active: boolean;
};

const props = defineProps<{
  form: InertiaForm<ServiceFormType>
  resolver: FormProps["resolver"]
  submitLabel: string
}>()

defineEmits(['submit', 'cancel'])

const durationTypeOptions = [
  { label: 'Minutes', value: 'minutes' },
  { label: 'Hours', value: 'hours' },
  { label: 'Days', value: 'days' },
  { label: 'Weeks', value: 'weeks' },
  { label: 'Months', value: 'months' }
]
</script>

<template>
  <Form :resolver="resolver" @submit="$emit('submit')" class="flex flex-col gap-4 max-w-xl w-full mx-auto">

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
      <template #title>Pricing
        <OptionalField />
      </template>
      <template #content>
        <div class="flex gap-4 flex-col lg:flex-row lg:gap-2 w-full">
          <div class="flex flex-col gap-1 w-full">
            <label>Base Price</label>
            <InputNumber fluid v-model="form.base_price" mode="currency" currency="USD" locale="en-US"
              :minFractionDigits="2" :maxFractionDigits="2" :invalid="Boolean(form.errors.base_price)" />
            <small class="text-red-500">{{ form.errors.base_price }}</small>
          </div>

          <div class="flex flex-col gap-1 w-full">
            <label>Price Type</label>


          </div>
        </div>
      </template>
    </Card>

    <Card>
      <template #title>Duration
        <OptionalField />
      </template>
      <template #content>
        <div class="flex gap-4 flex-col lg:flex-row lg:gap-2 w-full">
          <div class="flex flex-col gap-1 w-full">
            <label>Estimated Duration</label>
            <IconField class="w-full sm:w-auto">
              <InputIcon class="pi pi-clock" />
              <InputNumber fluid :invalid="Boolean(form.errors.estimated_duration_minutes)"
                v-model="form.estimated_duration_minutes" />
            </IconField>

            <small class="text-red-500">{{ form.errors.estimated_duration_minutes }}</small>
          </div>
          <div class="flex flex-col gap-1 w-full">
            <label>Duration Type</label>
            <Select v-model="form.estimated_duration_type" :invalid="Boolean(form.errors.estimated_duration_minutes)"
              :options="durationTypeOptions" optionLabel="label" placeholder="Select a duration type"
              optionValue="value" fluid />
            <Message v-if="form.errors.estimated_duration_type" severity="error" size="small" variant="simple">
              {{ form.errors.estimated_duration_type }}
            </Message>
          </div>
        </div>
      </template>
    </Card>

    <div class="flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <ToggleSwitch v-model="form.is_active" />
        <span :class="form.is_active ? 'font-bold' : ''">Active</span>
      </div>
      <Message v-if="form.errors.is_active" severity="error" size="small" variant="simple">
        {{ form.errors.is_active }}
      </Message>
    </div>

    <!-- Actions -->
    <div class="flex justify-end my-4">
      <Button label="Cancel" class="p-button-text mr-2" @click="$emit('cancel')" />
      <Button :label="submitLabel" :loading="form.processing" type="submit" />
    </div>

  </Form>
</template>