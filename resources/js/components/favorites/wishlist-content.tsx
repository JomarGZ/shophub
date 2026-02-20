import { PaginatedResponse, Product } from '@/types';
import { Pagination } from '../pagination';
import { WishlistEmptyState } from './wishlist-empty-state';
import { WishlistGrid } from './wishlist-grid';

type Props = {
    wishlistProducts: PaginatedResponse<Product>;
    addToCart: (product: Product) => void;
    loading: boolean;
};
export function WishlistContent({
    wishlistProducts,
    addToCart,
    loading,
}: Props) {
    if (wishlistProducts.meta.total === 0) {
        return <WishlistEmptyState />;
    }
    return (
        <>
            <WishlistGrid
                products={wishlistProducts.data}
                addToCart={addToCart}
                loading={loading}
            />
            <div className="flex justify-center pt-6">
                <Pagination links={wishlistProducts.meta.links} />
            </div>
        </>
    );
}
