import { Container } from '@/components/container';
import { WishlistContent } from '@/components/favorites/wishlist-content';
import { WishlistFeatureCards } from '@/components/favorites/wishlist-feature-cards';
import { WishlistHeader } from '@/components/favorites/wishlist-header';
import { useAddToCart } from '@/hooks/use-add-to-cart';
import AppLayout from '@/layouts/app-layout';
import { PaginatedResponse, WishlistProduct } from '@/types';
import { Head, usePage } from '@inertiajs/react';

type WishlistIndexProps = {
    wishlist_products: PaginatedResponse<WishlistProduct>;
};
export default function Index() {
    const { addToCart, loading } = useAddToCart();
    const { wishlist_products } = usePage<WishlistIndexProps>().props;
    return (
        <AppLayout>
            <Head title="Favorites" />
            <Container className="bg-background px-4 py-4">
                <WishlistHeader total={wishlist_products.meta.total} />
                <WishlistFeatureCards />
                <WishlistContent
                    wishlistProducts={wishlist_products}
                    addToCart={addToCart}
                    loading={loading}
                />
            </Container>
        </AppLayout>
    );
}
