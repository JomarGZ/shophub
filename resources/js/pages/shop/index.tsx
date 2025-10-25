import { Container } from '@/components/container';
import { Pagination } from '@/components/pagination';
import { ProductCard } from '@/components/products/product-card';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Slider } from '@/components/ui/slider';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/shop';
import { BreadcrumbItem, Category, PaginatedResponse, Product } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { Search } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: '#',
    },
];

interface ShopProps {
    products: PaginatedResponse<Product>;
    categories: Category[];
}
export default function Index({ products, categories }: ShopProps) {
    const {
        focus,
        price_range: serverPriceRange,
        filters = {
            search: '',
            categories: [],
            min_price: null,
            max_price: null,
        },
    } = usePage().props as unknown as {
        focus?: string;
        price_range: { min: number; max: number };
        filters?: {
            search?: string;
            categories: string[];
            min_price?: number;
            max_price?: number;
        };
    };
    const [term, setTerm] = useState(filters.search ?? '');
    const [selectedCategorySlugs, setSelectedCategorySlugs] = useState<
        string[]
    >(filters.categories ?? []);
    const [priceRange, setPriceRange] = useState<number[]>([
        filters.min_price ?? serverPriceRange.min,
        filters.max_price ?? serverPriceRange.max,
    ]);
    const inputRef = useRef<HTMLInputElement>(null);
    useEffect(() => {
        if (focus === 'search' && inputRef.current) {
            inputRef.current.focus();
        }
    }, [focus]);

    useEffect(() => {
        const hasLocalChanges =
            term !== (filters.search ?? '') ||
            JSON.stringify(selectedCategorySlugs) !==
                JSON.stringify(filters.categories ?? []) ||
            priceRange[0] !== (filters.min_price ?? serverPriceRange.min) ||
            priceRange[1] !== (filters.max_price ?? serverPriceRange.max);

        if (!hasLocalChanges) {
            return;
        }
        const timeout = setTimeout(() => {
            const options = {
                query: {
                    ...(term && { search: term }),
                    ...(selectedCategorySlugs.length > 0 && {
                        categories: selectedCategorySlugs,
                    }),
                    ...(priceRange[0] > serverPriceRange.min && {
                        min_price: priceRange[0],
                    }),
                    ...(priceRange[1] < serverPriceRange.max && {
                        max_price: priceRange[1],
                    }),
                },
            };
            const url = index.url(options);
            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            });
        }, 500);

        return () => clearTimeout(timeout);
    }, [term, selectedCategorySlugs, priceRange]);

    const toggleCategory = (category: string) => {
        setSelectedCategorySlugs((prev) =>
            prev.includes(category)
                ? prev.filter((c) => c !== category)
                : [...prev, category],
        );
    };
    const handleAddToCart = (product: Product) => {
        console.log('Add to cart:', product);
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Shop" />
            <Container
                className="container mx-auto flex-1 px-4 py-8"
                innerClassName="grid grid-cols-1 lg:grid-cols-4 gap-8"
            >
                <aside className="lg:col-span-1">
                    <div className="sticky top-24 rounded-lg bg-card p-6 shadow-card">
                        <h2 className="mb-6 text-xl font-bold text-foreground">
                            Filters
                        </h2>

                        {/* Categories */}
                        <div className="mb-6">
                            <Label className="mb-3 block text-base font-semibold text-secondary">
                                Categories
                            </Label>
                            <div className="space-y-3">
                                {categories.map((category) => (
                                    <div
                                        key={category.slug}
                                        className="flex items-center gap-2"
                                    >
                                        <Checkbox
                                            id={`cat-${category.slug}`} // must be string and unique
                                            checked={selectedCategorySlugs.includes(
                                                category.slug,
                                            )}
                                            onCheckedChange={() =>
                                                toggleCategory(category.slug)
                                            }
                                        />
                                        <label
                                            htmlFor={`cat-${category.slug}`}
                                            className="cursor-pointer text-sm leading-none font-medium"
                                        >
                                            {category.name}
                                        </label>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Price Range */}
                        <div>
                            <Label className="mb-3 block text-base font-semibold text-secondary">
                                Price Range
                            </Label>
                            <div className="space-y-4">
                                <Slider
                                    min={serverPriceRange.min}
                                    max={serverPriceRange.max}
                                    step={10}
                                    value={priceRange}
                                    onValueChange={(value) =>
                                        setPriceRange(value as [number, number])
                                    }
                                    className="w-full"
                                />
                                <div className="flex items-center justify-between text-sm text-muted-foreground">
                                    <span>${priceRange[0]}</span>
                                    <span>${priceRange[1]}</span>
                                </div>
                            </div>
                        </div>

                        {/* Reset Button */}
                        <Button
                            variant="outline"
                            className="mt-6 w-full"
                            onClick={() => {
                                setPriceRange([
                                    serverPriceRange.min,
                                    serverPriceRange.max,
                                ]);
                                setSelectedCategorySlugs([]);
                            }}
                        >
                            Reset Filters
                        </Button>
                    </div>
                </aside>
                <div className="lg:col-span-3">
                    <div className="mb-6">
                        <div className="relative">
                            <Search className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                type="search"
                                ref={inputRef}
                                value={term}
                                onChange={(e) => setTerm(e.target.value)}
                                placeholder="Search products by name or category..."
                                className="h-12 border-border bg-card pl-11 text-base shadow-card"
                            />
                        </div>
                    </div>
                    <div className="mb-6 flex items-center justify-between">
                        <h1 className="text-3xl font-bold text-foreground">
                            All Products
                        </h1>
                        <p className="text-muted-foreground">
                            {products.data.length} products found
                        </p>
                    </div>

                    <div className="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {products.data.map((product) => (
                            <ProductCard
                                key={product.id}
                                product={product}
                                onAddToCart={handleAddToCart}
                            />
                        ))}
                    </div>
                    {/* Pagination */}
                    <Pagination links={products.meta.links} />
                </div>
            </Container>
        </AppLayout>
    );
}
