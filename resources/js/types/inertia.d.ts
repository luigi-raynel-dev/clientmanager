// resources/js/types/inertia.d.ts
import { PageProps } from '@inertiajs/core';

export interface FlashProps {
    success?: string;
    error?: string;
    warning?: string;
    info?: Record<string, any>;
}

declare module '@inertiajs/core' {
    interface PageProps extends PageProps {
        flash?: FlashProps;
    }
}
