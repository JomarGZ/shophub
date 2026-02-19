import { Container } from '@/components/container';
import { ShopFiltersSidebar } from '@/components/shop/ShopFiltersSidebar';
import { ShopProductGrid } from '@/components/shop/ShopProductGrid';
import { ShopSearchBar } from '@/components/shop/ShopSearchBar';
import { useAddToCart } from '@/hooks/use-add-to-cart';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/shop';
import { Category, PaginatedResponse, Product } from '@/types';
import { PriceRange, ShopFilters } from '@/types/shop';
import { Head, router, usePage } from '@inertiajs/react';
import pickBy from 'lodash.pickby';
import { useEffect, useState } from 'react';
import { usePrevious } from 'react-use';
import { useDebounce } from 'use-debounce';
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

    const [values, setValues] = useState({
        search: filters.search || '',
        categories: filters.categories || [],
        min_price: filters.min_price || price_range.min,
        max_price: filters.max_price || price_range.max,
    });

    const handleFilterChange = (
        values: Record<string, any>,
        prevValues: Record<string, any> | undefined,
    ) => {
        if (!prevValues) return;
        const query = Object.keys(pickBy(values)).length ? pickBy(values) : {};

        router.get(index.url(), query, {
            replace: true,
            preserveState: true,
        });
    };

    const handleChange = (name: string, value: any) => {
        setValues((values) => ({
            ...values,
            [name]: value,
        }));
    };

    const handelReset = () => {
        setValues({
            search: '',
            categories: [],
            min_price: price_range.min,
            max_price: price_range.max,
        });
    };

    const prevValues = usePrevious(values);
    const [debounceValues] = useDebounce(values, 500);

    useEffect(() => {
        handleFilterChange(debounceValues, prevValues);
    }, [debounceValues]);

    return (
        <AppLayout>
            <Head title="Shop" />
            <Container innerClassName="grid lg:grid-cols-4 gap-8">
                <aside>
                    <ShopFiltersSidebar
                        categories={categories}
                        values={values}
                        onChange={handleChange}
                        onReset={handelReset}
                        priceRange={price_range}
                    />
                </aside>

                <div className="lg:col-span-3">
                    <ShopSearchBar
                        value={values.search}
                        onChange={(value) => handleChange('search', value)}
                    />
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
