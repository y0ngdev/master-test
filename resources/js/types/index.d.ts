import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface Plan {
    id: number;
    name: string;
    short_description: string;
    gateway_meta: object;
    price: number;
    features: Array;
    is_featured: string;
    sort_order: number;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}
export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    settings: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
