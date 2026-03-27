<script setup lang="ts">
import { store } from '@/routes/pricingTypes';
import { useForm } from '@inertiajs/vue3';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { Button, Dialog, InputText, Message } from 'primevue'
import { ref } from 'vue';
import z from 'zod';

const props = defineProps<{
  visible: boolean
}>()

const emit = defineEmits(['update:visible', 'cancel'])

const close = () => {
  emit('update:visible', false)
}

// Zod schema
const pricingTypeSchema = z.object({
  name: z.string().min(1).max(255),
})

const resolver = ref(zodResolver(pricingTypeSchema))

// Inertia form
const form = useForm({
  name: '',
})

const submit = () => {
  const result = pricingTypeSchema.safeParse(form.data())
  form.clearErrors()

  if (!result.success) {
    const { fieldErrors } = z.flattenError(result.error);
    form.setError("name", fieldErrors.name?.[0] || "Invalid name")
    return
  }

  form.post(store().url, {
    onSuccess: () => {
      close()
    },
    onError: (errors) => {
      if (errors.name) {
        form.setError("name", errors.name)
      }
    }
  })
}

</script>

<template>
  <Dialog :visible="props.visible" @update:visible="(value) => emit('update:visible', value)" modal
    header="New Pricing Type" :style="{ width: '25rem' }">
    <Form :resolver="resolver" @submit.prevent="submit" class="flex flex-col gap-4 max-w-xl w-full mx-auto">
      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
          <label for="name" class="font-semibold w-24">Name</label>
          <InputText :invalid="Boolean(form.errors.name)" v-model="form.name" fluid id="name" class="flex-auto"
            autocomplete="off" />
          <Message v-if="form.errors.name" severity="error" size="small" variant="simple">
            {{ form.errors.name }}
          </Message>
        </div>
        <div class="flex justify-end gap-2">
          <Button type="button" label="Cancel" severity="secondary" @click="close"></Button>
          <Button type="submit" :loading="form.processing">Add</Button>
        </div>
      </div>
    </Form>
  </Dialog>
</template>