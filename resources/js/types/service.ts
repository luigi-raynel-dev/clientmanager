import { SortOption } from '.';

export type Service = {
    id: number;
    name: string;
    description?: string;
    base_price?: number;
    price_type?: string;
    other_price_type?: string;
    estimated_duration_minutes?: number;
    estimated_duration_type: 'minutes' | 'hours' | 'days' | 'weeks' | 'months';
    is_active: boolean;
    created_at: string;
    updated_at: string;
};

export type ServiceFiltersType = SortOption & {
    is_active?: number;
    per_page?: number;
};
