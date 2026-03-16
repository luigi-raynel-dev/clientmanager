export type Service = {
    id: number;
    name: string;
    description?: string;
    base_price?: number;
    price_type?: string;
    other_price_type?: string;
    estimated_duration_hours?: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
};

export type ServiceFiltersType = {
    is_active?: boolean;
    order_by?: string;
    order_direction?: 'asc' | 'desc';
    per_page?: number;
};
