<script setup lang="ts">
import { Form, FormProps } from '@primevue/forms'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { InertiaForm } from '@inertiajs/vue3'
import { Card, Select, SelectButton, Textarea } from 'primevue';
import OptionalField from '../ui/label/OptionalField.vue';
import ClientMultiSelect from '../Client/ClientMultiSelect.vue';
import ProfessionalMultiSelect from './ProfessionalMultiSelect.vue';
import type { User } from '@/types/auth';
import { ProjectStatus } from '@/types/project';
import { Client } from '@/types/client';
import { Service } from '@/types/service';
import { ref } from 'vue';
import AddServices from '../Service/AddServices.vue';

export type ProjectFormType = {
  name: string;
  description?: string;
  priority: 'Low' | 'Medium' | 'High';
  status_id?: number | null;
  start_date?: string | null;
  end_date?: string | null;
  professional_ids: number[];
  client_ids: number[];
};

const props = defineProps<{
  form: InertiaForm<ProjectFormType>
  statuses: ProjectStatus[]
  professionals: User[]
  clients: Client[]
  services: Service[]
  resolver: FormProps["resolver"]
  submitLabel: string
}>()

const openNewService = ref(false)

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
              <Button icon="pi pi-plus" iconPos="right" label="Add Service" class="p-button-sm" severity="info"
                @click="openNewService = true" />
            </div>
            <AddServices :visible="openNewService" :services="services" />
          </template>
        </Card>

      </div>
    </div>

    <div class="w-full md:w-1/3 md:self-start">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="flex flex-col">
              <h2 class="text-xl font-bold mb-2">Priority</h2>
              <SelectButton fluid v-model="form.priority" :options="[
                { value: 'Low', icon: 'pi pi-flag' },
                { value: 'Medium', icon: 'pi pi-flag' },
                { value: 'High', icon: 'pi pi-flag-fill' }
              ]" optionLabel="value" optionValue="value" dataKey="value" aria-labelledby="custom">
                <template #option="slotProps">
                  <i :class="slotProps.option.icon"
                    :style="{ color: slotProps.option.value === 'Low' ? '#3b82f6' : slotProps.option.value === 'Medium' ? '#f59e0b' : '#ef4444' }"></i>
                </template>
              </SelectButton>
            </div>
            <div class="flex flex-col">
              <h2 class="text-xl font-bold mb-2">Status</h2>
              <Select v-model="form.status_id" :options="statuses" optionLabel="title" placeholder="Select a status"
                optionValue="id" fluid>
                <template #value="slotProps">
                  <div v-if="statuses.find(s => s.id === slotProps.value)" class="flex items-center gap-2">
                    <div class="border border-black w-3 h-3 rounded-lg"
                      :style="{ backgroundColor: statuses.find(s => s.id === slotProps.value)!.color }">
                    </div>
                    <div>{{statuses.find(s => s.id === slotProps.value)!.title}}</div>
                  </div>
                  <span v-else>
                    {{ slotProps.placeholder }}
                  </span>
                </template>
                <template #option="slotProps">
                  <div class="flex items-center gap-2">
                    <div class="border border-black w-3 h-3 rounded-lg"
                      :style="{ backgroundColor: slotProps.option.color }">
                    </div>
                    <div>
                      {{ slotProps.option.title }}
                    </div>
                  </div>
                </template>
              </Select>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="flex flex-col gap-1">
                <label>Start Date
                  <OptionalField />
                </label>
                <input type="date" class="p-inputtext w-full" v-model="form.start_date" />
              </div>
              <div class="flex flex-col gap-1">
                <label>End Date
                  <OptionalField />
                </label>
                <input type="date" class="p-inputtext w-full" v-model="form.end_date" />
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <h2 class="text-xl font-bold">Clients</h2>
              <p class="text-gray-600">You can assign clients to follow this project.</p>
              <ClientMultiSelect v-model="form.client_ids" :clients="props.clients" :error="form.errors.client_ids" />
            </div>
            <div class="flex flex-col gap-2">
              <h2 class="text-xl font-bold">Professionals</h2>
              <p class="text-gray-600">You can assign professionals to work in this project.</p>
              <ProfessionalMultiSelect v-model="form.professional_ids" :professionals="props.professionals"
                :error="form.errors.professional_ids" />
            </div>
          </div>
        </template>
      </Card>

      <!-- Actions -->
      <div class="flex justify-end my-4">
        <Button label="Cancel" class="p-button-text mr-2" @click="$emit('cancel')" />
        <Button :label="submitLabel" :loading="form.processing" type="submit" />
      </div>
    </div>

  </Form>
</template>