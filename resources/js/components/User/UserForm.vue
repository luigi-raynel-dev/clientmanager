<script setup lang="ts">
import { Form } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import { Message, SelectButton, ToggleSwitch } from 'primevue'

defineProps<{
  form: any
  resolver: any
  showPassword?: boolean
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

    <!-- Role -->
    <div class="flex flex-col gap-1">
      <label>Role</label>
      <SelectButton fluid v-model="form.role" :invalid="Boolean(form.errors.role)" :options="[
        { label: 'Admin', value: 'admin' },
        { label: 'User', value: 'user' }
      ]" optionLabel="label" optionValue="value" />
      <small class="text-red-500">{{ form.errors.role }}</small>
    </div>

    <!-- Passwords only when needed -->
    <template v-if="showPassword">
      <div class="flex flex-col gap-1">
        <label>Password</label>
        <Password v-model="form.password" fluid toggleMask />
        <small class="text-red-500">{{ form.errors.password }}</small>
      </div>

      <div class="flex flex-col gap-1">
        <label>Confirm Password</label>
        <Password v-model="form.password_confirmation" fluid toggleMask :feedback="false" />
        <small class="text-red-500">{{ form.errors.password_confirmation }}</small>
      </div>
    </template>

    <!-- Active -->
    <div class="flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <ToggleSwitch v-model="form.active" />
        <span :class="form.active ? 'font-bold' : ''">Active</span>
      </div>
      <Message v-if="form.errors.active" severity="error" size="small" variant="simple">
        {{ form.errors.active }}
      </Message>
    </div>

    <!-- Actions -->
    <div class="flex justify-end my-4">
      <Button label="Cancel" class="p-button-text mr-2" @click="$emit('cancel')" />
      <Button :label="submitLabel" :icon="submitIcon" :loading="form.processing" type="submit" />
    </div>

  </Form>
</template>