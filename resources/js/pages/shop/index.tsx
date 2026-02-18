import { Container } from '@/components/container';
import { ShopFiltersSidebar } from '@/components/shop/ShopFiltersSidebar';
import { ShopProductGrid } from '@/components/shop/ShopProductGrid';
import { ShopSearchBar } from '@/components/shop/ShopSearchBar';
import { useShopFilters } from '@/hooks/shop/use-shop-filters';
import { useAddToCart } from '@/hooks/use-add-to-cart';
import AppLayout from '@/layouts/app-layout';
import { Category, PaginatedResponse, Product } from '@/types';
import { PriceRange, ShopFilters } from '@/types/shop';
import { Head, usePage } from '@inertiajs/react';
import { useRef } from 'react';

type ShopPageProps = {
    products: PaginatedResponse<Product>;
    categories: Category[];
    price_range: PriceRange;
    filters: ShopFilters;
    focus?: string;
};
export default function Index() {
    const { addToCart, loading } = useAddToCart();

    const { products, categories, price_range, filters } =
        usePage<ShopPageProps>().props;

    const filterState = useShopFilters(filters, price_range);

    const inputRef = useRef<HTMLInputElement>(null);

    return (
        <AppLayout>
            <Head title="Shop" />
            <Container innerClassName="grid lg:grid-cols-4 gap-8">
                <aside>
                    <ShopFiltersSidebar
                        categories={categories}
                        selectedCategories={filterState.categories}
                        toggleCategory={filterState.toggleCategory}
                        priceRange={filterState.priceRange}
                        setPriceRange={filterState.setPriceRange}
                        serverPriceRange={price_range}
                        reset={filterState.reset}
                    />
                </aside>

                <div className="lg:col-span-3">
                    <ShopSearchBar />
                    <ShopProductGrid
                        products={products}
                        addToCart={addToCart}
                        loading={loading}
                    />
                </div>
            </Container>
        </AppLayout>
    );
}
