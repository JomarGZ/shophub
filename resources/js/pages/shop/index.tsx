import { Container } from '@/components/container';
import { ProductCard } from '@/components/products/product-card';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Slider } from '@/components/ui/slider';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, Product } from '@/types';
import { Head } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: '#',
    },
];

interface ShopProps {
    products: Product[];
    categories: string[];
}
export default function Index({ products, categories }: ShopProps) {
    const [priceRange, setPriceRange] = useState([0, 250]);
    const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
    const toggleCategory = (category: string) => {
        setSelectedCategories((prev) =>
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
                                        key={category}
                                        className="flex items-center gap-2"
                                    >
                                        <Checkbox
                                            id={category}
                                            checked={selectedCategories.includes(
                                                category,
                                            )}
                                            onCheckedChange={() =>
                                                toggleCategory(category)
                                            }
                                        />
                                        <label
                                            htmlFor={category}
                                            className="cursor-pointer text-sm leading-none font-medium"
                                        >
                                            {category}
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
                                    min={0}
                                    max={250}
                                    step={10}
                                    value={priceRange}
                                    onValueChange={setPriceRange}
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
                                setPriceRange([0, 250]);
                                setSelectedCategories([]);
                            }}
                        >
                            Reset Filters
                        </Button>
                    </div>
                </aside>
                <div className="lg:col-span-3">
                    <div className="mb-6 flex items-center justify-between">
                        <h1 className="text-3xl font-bold text-foreground">
                            All Products
                        </h1>
                        <p className="text-muted-foreground">
                            {products.length} products found
                        </p>
                    </div>

                    <div className="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {products.map((product) => (
                            <ProductCard
                                key={product.id}
                                product={product}
                                onAddToCart={handleAddToCart}
                            />
                        ))}
                    </div>

                    {/* Pagination */}
                    <div className="flex items-center justify-center gap-2">
                        <Button variant="outline" size="icon">
                            <ChevronLeft className="h-4 w-4" />
                        </Button>
                        <Button variant="default" size="icon">
                            1
                        </Button>
                        <Button variant="outline" size="icon">
                            2
                        </Button>
                        <Button variant="outline" size="icon">
                            3
                        </Button>
                        <Button variant="outline" size="icon">
                            <ChevronRight className="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </Container>
        </AppLayout>
    );
}
