import { Container } from '@/components/container';
import { ProductCard } from '@/components/products/product-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useAddToCart } from '@/hooks/use-add-to-cart';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/shop';
import { BreadcrumbItem, Product } from '@/types';
import { Head } from '@inertiajs/react';
import { Heart, Minus, Plus, Share2, ShoppingCart, Star } from 'lucide-react';
import { useState } from 'react';

export default function Show({
    product,
    related_products,
}: {
    product: Product;
    related_products: Product[];
}) {
    const { addToCart, loading } = useAddToCart();
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Shop',
            href: index().url,
        },
        {
            title: product.name,
            href: '#',
        },
    ];
    const [quantity, setQuantity] = useState(1);
    const [mainLoading, setMainLoading] = useState(false);
    const increment = () => setQuantity((prev) => prev + 1);
    const decrement = () => setQuantity((prev) => Math.max(prev - 1, 1));
    const handleAddToCart = () => {
        console.log(addToCart(product, quantity));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Shop" />
            <Container
                as="section"
                className="px-4"
                innerClassName="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-8"
            >
                <div className="aspect-square overflow-hidden rounded-lg bg-muted shadow-card">
                    <img
                        src={product.image_url}
                        alt={product.name}
                        className="h-full w-full object-cover"
                    />
                </div>
                <div className="space-y-6">
                    <div>
                        <Badge variant="secondary" className="mb-3">
                            {product.category.name}
                        </Badge>
                        <h1 className="mb-4 text-4xl font-bold text-foreground">
                            {product.name}
                        </h1>
                        <div className="mb-4 flex items-center gap-4">
                            <div className="flex items-center gap-1">
                                {[...Array(5)].map((_, i) => (
                                    <Star
                                        key={i}
                                        className={`h-5 w-5 ${
                                            i < Math.floor(product.rating)
                                                ? 'fill-primary text-primary'
                                                : 'text-muted'
                                        }`}
                                    />
                                ))}
                            </div>
                            <span className="text-sm text-muted-foreground">
                                {product.rating} (
                                {Math.floor(Math.random() * 100 + 20)} reviews)
                            </span>
                        </div>
                        <p className="text-5xl font-bold text-foreground">
                            ${product.price}
                        </p>
                    </div>

                    <div className="space-y-4 border-t border-b border-border py-6">
                        <p className="leading-relaxed text-foreground">
                            {product.description}
                        </p>
                        <div className="flex items-center gap-2">
                            <span className="text-sm font-medium text-secondary">
                                Availability:
                            </span>
                            {product.stock > 0 ? (
                                <Badge variant="default" className="bg-primary">
                                    {product.stock} in stock
                                </Badge>
                            ) : (
                                <Badge variant="destructive">
                                    Out of stock
                                </Badge>
                            )}
                        </div>
                    </div>

                    {/* Quantity Selector */}
                    <div className="space-y-4">
                        <Label className="text-sm font-semibold text-secondary">
                            Quantity
                        </Label>
                        <div className="flex items-center gap-4">
                            <div className="flex items-center rounded-lg border border-border">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    onClick={decrement}
                                    disabled={quantity <= 1}
                                >
                                    <Minus className="h-4 w-4" />
                                </Button>
                                <span className="w-12 text-center font-semibold">
                                    {quantity}
                                </span>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    onClick={increment}
                                    disabled={quantity >= product.stock}
                                >
                                    <Plus className="h-4 w-4" />
                                </Button>
                            </div>
                            <span className="text-sm text-muted-foreground">
                                Total: ${(product.price * quantity).toFixed(2)}
                            </span>
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="flex gap-3">
                        <Button
                            onClick={handleAddToCart}
                            className="h-12 flex-1 bg-primary text-base text-primary-foreground hover:bg-primary/90"
                            disabled={product.stock === 0 || mainLoading}
                        >
                            <ShoppingCart className="mr-2 h-5 w-5" />
                            Add to Cart
                        </Button>
                        <Button
                            variant="outline"
                            size="icon"
                            className="h-12 w-12"
                        >
                            <Heart className="h-5 w-5" />
                        </Button>
                        <Button
                            variant="outline"
                            size="icon"
                            className="h-12 w-12"
                        >
                            <Share2 className="h-5 w-5" />
                        </Button>
                    </div>
                </div>
            </Container>
            <Container as="section">
                <h2 className="mb-8 text-3xl font-bold text-foreground">
                    Related Products
                </h2>
                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {related_products.map((related_product) => (
                        <ProductCard
                            loading={loading}
                            key={related_product.id}
                            product={related_product}
                            onAddToCart={addToCart}
                        />
                    ))}
                </div>
            </Container>
        </AppLayout>
    );
}
