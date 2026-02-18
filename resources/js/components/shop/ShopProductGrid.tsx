import { Pagination } from '@/components/pagination';
import { ProductCard } from '@/components/products/product-card';
import { PaginatedResponse, Product } from '@/types';

interface Props {
    products: PaginatedResponse<Product>;
    addToCart: any;
    loading: boolean;
}

export function ShopProductGrid({ products, addToCart, loading }: Props) {
    return (
        <>
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {products.data.map((product) => (
                    <ProductCard
                        key={product.id}
                        product={product}
                        onAddToCart={addToCart}
                        loading={loading}
                        isFavorite={product.is_favorited}
                    />
                ))}
            </div>

            <Pagination links={products.meta.links} />
        </>
    );
}
