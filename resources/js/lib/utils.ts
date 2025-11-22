import { badgeVariants } from '@/components/ui/badge';
import { VariantProps } from 'class-variance-authority';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}
type BadgeVariant = NonNullable<VariantProps<typeof badgeVariants>['variant']>;
export const orderStatusVariants: Record<string, BadgeVariant> = {
    pending: 'warning', // amber
    processing: 'info', // blue
    preparing_for_shipment: 'accent', // cyan
    shipped: 'neutral', // slate
    out_for_delivery: 'info', // sky
    delivered: 'success', // green
};

export const paymentStatusVariants: Record<string, BadgeVariant> = {
    unpaid: 'destructive',
    paid: 'success',
    refunded: 'warning',
    cancelled: 'destructive',
};

export function getOrderVariant(status: string) {
    return orderStatusVariants[status] ?? 'destructive';
}

export function getPaymentVariant(status: string) {
    return paymentStatusVariants[status] ?? 'destructive';
}
