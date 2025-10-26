import { store } from '@/routes/cart';
import { Product } from '@/types';
import { router } from '@inertiajs/react';
import { toast } from 'sonner';

export function useAddToCart() {
    const addToCart = (product: Product) => {
        router.post(
            store(),
            {
                product_id: product.id,
            },
            {
                onSuccess: () =>
                    toast.success(`${product.name} added to carttt!`),
                onError: () => toast.error('Failed to add to cart'),
                preserveScroll: true,
            },
        );
    };

    return { addToCart };
}
