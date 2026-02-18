import { index } from '@/routes/shop';
import { PriceRange, ShopFilters } from '@/types/shop';
import { router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export function useShopFilters(
    filters: ShopFilters,
    serverPriceRange: PriceRange,
) {
    const [term, setTerm] = useState(filters.search ?? '');
    const [categories, setCategories] = useState<string[]>(
        filters.categories ?? [],
    );

    const [priceRange, setPriceRange] = useState<number[]>([
        filters.min_price ?? serverPriceRange.min,
        filters.max_price ?? serverPriceRange.max,
    ]);

    useEffect(() => {
        const hasLocalChanges =
            term !== (filters.search ?? '') ||
            JSON.stringify(categories) !==
                JSON.stringify(filters.categories ?? []) ||
            priceRange[0] !== (filters.min_price ?? serverPriceRange.min) ||
            priceRange[1] !== (filters.max_price ?? serverPriceRange.max);

        if (!hasLocalChanges) return;

        const timeout = setTimeout(() => {
            const url = index.url({
                query: {
                    ...(term && { search: term }),
                    ...(categories.length && { categories }),
                    ...(priceRange[0] > serverPriceRange.min && {
                        min_price: priceRange[0],
                    }),
                    ...(priceRange[1] < serverPriceRange.max && {
                        max_price: priceRange[1],
                    }),
                },
            });

            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            });
        }, 500);

        return () => clearTimeout(timeout);
    }, [term, categories, priceRange]);

    function toggleCategory(slug: string) {
        setCategories((prev) =>
            prev.includes(slug)
                ? prev.filter((c) => c !== slug)
                : [...prev, slug],
        );
    }

    function reset() {
        setCategories([]);
        setPriceRange([serverPriceRange.min, serverPriceRange.max]);
        setTerm('');
    }

    return {
        term,
        setTerm,
        categories,
        toggleCategory,
        priceRange,
        setPriceRange,
        reset,
    };
}
