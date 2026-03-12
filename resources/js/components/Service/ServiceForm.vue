<script setup lang="ts">
import { Form, FormProps } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { InertiaForm } from '@inertiajs/vue3'
import { AutoComplete, Card, IconField, InputIcon, InputNumber, Message, Textarea, ToggleSwitch } from 'primevue';
import { ref } from 'vue'
import OptionalField from '../ui/label/OptionalField.vue';

export type ServiceFormType = {
  name: string;
  description?: string;
  base_price?: number;
  price_type?: string;
  other_price_type?: string;
  estimated_duration_hours?: number;
  is_active: boolean;
};

const props = defineProps<{
  form: InertiaForm<ServiceFormType>
  resolver: FormProps["resolver"]
  submitLabel: string
}>()

defineEmits(['submit', 'cancel'])

const priceTypeOptions = [
  { label: 'Fixed Price', value: 'fixed' },
  { label: 'Per Unit', value: 'unit' },
  { label: 'Per Hour', value: 'hourly' },
  { label: 'Per Day', value: 'daily' }
]

const filteredPriceTypes = ref(priceTypeOptions)

const searchPriceType = (event: any) => {
  const query = event.query.toLowerCase()

  filteredPriceTypes.value = priceTypeOptions.filter(option =>
    option.label.toLowerCase().includes(query)
  )
}

const handlePriceTypeChange = (value: any) => {
  if (!value) return

  if (typeof value === 'object') {
    props.form.price_type = value.value
    props.form.other_price_type = undefined
  }

  else {
    const found = priceTypeOptions.find(o => o.value === value)

    if (found) {
      props.form.price_type = found.value
      props.form.other_price_type = undefined
    } else {
      props.form.price_type = undefined
      props.form.other_price_type = value
    }
  }
}
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

    <div class="flex flex-col gap-1">
      <label>Estimated Duration (hours)
        <OptionalField />
      </label>
      <IconField class="w-full sm:w-auto">
        <InputIcon class="pi pi-clock" />
        <InputNumber fluid :invalid="Boolean(form.errors.estimated_duration_hours)"
          v-model="form.estimated_duration_hours" />
      </IconField>

      <small class="text-red-500">{{ form.errors.estimated_duration_hours }}</small>
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

            <AutoComplete fluid dropdown :suggestions="filteredPriceTypes" optionLabel="label" :forceSelection="false"
              @complete="searchPriceType" @update:modelValue="handlePriceTypeChange"
              :modelValue="form.price_type ?? form.other_price_type" />

            <small class="text-red-500">
              {{ form.errors.price_type || form.errors.other_price_type }}
            </small>
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