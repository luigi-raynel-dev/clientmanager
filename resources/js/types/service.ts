import { SortOption } from '.';

export type PricingType = {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
};

export type Service = {
    id: number;
    name: string;
    description?: string;
    base_price?: number;
    pricing_type_id?: number | null;
    estimated_duration_minutes?: number;
    estimated_duration_type: 'minutes' | 'hours' | 'days' | 'weeks' | 'months';
    is_active: boolean;
    created_at: string;
    updated_at: string;
    pricing_type?: PricingType | null;
};

export type ServiceFiltersType = SortOption & {
    is_active?: number;
    per_page?: number;
};
