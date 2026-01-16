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

export function formatCount(count: number): string {
    const num = Number(count);
    if (!Number.isFinite(num) || num < 0) return '0';

    if (count >= 1_000_000) {
        return (count / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
    } else if (count >= 1_000) {
        return (count / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
    }

    return count.toString();
}

export function formatRatingCount(count: number): string {
    const num = Number(count);
    const formatted = formatCount(num);
    const label = count > 1 ? 'ratings' : 'rating';
    return `${formatted} ${label}`;
}
