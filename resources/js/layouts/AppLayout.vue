<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Toast, useToast } from 'primevue';
import { watch } from 'vue';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});


const page = usePage()
const toast = useToast()

watch(
    () => page.props.flash?.success,
    (msg) => {
        if (msg) {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: msg,
                life: 3000,
            })
        }
    }
)
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <slot />
    </AppLayout>
</template>
