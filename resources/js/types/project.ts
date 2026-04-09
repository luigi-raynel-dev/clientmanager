import { Service } from './service';

export type ProjectStatus = {
    id: number;
    title: string;
    description: string;
    is_default?: boolean;
    is_final?: boolean;
    color: string;
    order: number;
};

export type Project = {
    id: number;
    name: string;
    description?: string;
    created_at: string;
    updated_at: string;
    services: Service[];
    status?: ProjectStatus;
};
