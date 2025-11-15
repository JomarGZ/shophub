import { store } from '@/routes/cart';
import { Product } from '@/types';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';

export function useAddToCart() {
    const [loading, setLoading] = useState(false);
    const addToCart = (
        product: Product,
        quantity: number = 1,
        extraOptions: Parameters<typeof router.post>[2] = {},
    ) => {
        if (loading) return;
        setLoading(true);
        router.post(
            store(),
            {
                product_id: product.id,
                quantity: quantity,
            },
            {
                onSuccess: () =>
                    toast.success(`${product.name} added to cart!`),
                onError: () => toast.error('Failed to add to cart'),
                onFinish: () => setLoading(false),
                preserveScroll: true,
                only: ['cart_items'],
                ...extraOptions,
            },
        );
    };

    return { addToCart, loading };
}
