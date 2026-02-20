import { Product } from '@/types';
import { ProductCard } from '../products/product-card';

type Props = {
    products: Product[];
    addToCart: (product: Product) => void;
    loading: boolean;
};
export function WishlistGrid({ products, addToCart, loading }: Props) {
    return (
        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {products.map((product) => (
                <ProductCard
                    key={product.id}
                    product={product}
                    onAddToCart={addToCart}
                    loading={loading}
                    isFavorite={true}
                />
            ))}
        </div>
    );
}
