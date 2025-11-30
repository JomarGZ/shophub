import { Container } from '@/components/container';
import { ProductCard } from '@/components/products/product-card';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useAddToCart } from '@/hooks/use-add-to-cart';
import AppLayout from '@/layouts/app-layout';
import shop from '@/routes/shop';
import { SimplePaginatedResponse, WishlistProduct } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Heart, ShoppingBag, Trash2 } from 'lucide-react';
import { useState } from 'react';

export default function Index({
    wishlist_products,
}: {
    wishlist_products: any;
}) {
    const { addToCart, loading } = useAddToCart();
    const [wishlistProducts, setWishlistProducts] = useState<WishlistProduct[]>(
        wishlist_products.data,
    );
    const [nextPageUrl, SetNextPageUrl] = useState<string | null>(
        wishlist_products.next_page_url
            ? String(wishlist_products.next_page_url)
            : null,
    );
    const [hasMore, setHasMore] = useState<Boolean>(
        wishlist_products.has_more || false,
    );
    const loadMore = () => {
        if (!hasMore || !nextPageUrl) return;

        router.get(
            nextPageUrl,
            {},
            {
                preserveScroll: true,
                preserveState: true,
                only: ['wishlist_products'],
                onSuccess: (page) => {
                    console.log(page);
                    const wishlistProducts = page.props
                        .wishlist_products as SimplePaginatedResponse<WishlistProduct>;
                    setWishlistProducts((prev) => [
                        ...prev,
                        ...wishlistProducts.data,
                    ]);
                    SetNextPageUrl(
                        wishlistProducts.next_page_url
                            ? String(wishlistProducts.next_page_url)
                            : null,
                    );
                    setHasMore(wishlistProducts.has_more);
                },
            },
        );
    };
    return (
        <AppLayout>
            <Head title="Favorites" />
            <Container className="bg-background px-4 py-4">
                <div className="animate-fade-in mb-8">
                    <div className="mb-2 flex items-center gap-3">
                        <div className="bg-gradient-hero flex h-12 w-12 items-center justify-center rounded-full">
                            <Heart className="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 className="text-3xl font-bold text-foreground">
                                My Favorites
                            </h1>
                            <p className="text-muted-foreground">
                                {wishlistProducts.length}{' '}
                                {wishlistProducts.length === 1
                                    ? 'item'
                                    : 'items'}{' '}
                                saved
                            </p>
                        </div>
                    </div>
                </div>
                <div className="animate-fade-in mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <Card className="border-border">
                        <CardContent className="flex items-start gap-3 p-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                                <Heart className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <h3 className="mb-1 font-semibold text-foreground">
                                    Save for Later
                                </h3>
                                <p className="text-sm text-muted-foreground">
                                    Keep track of products you're interested in
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-border">
                        <CardContent className="flex items-start gap-3 p-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                                <ShoppingBag className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <h3 className="mb-1 font-semibold text-foreground">
                                    Quick Add to Cart
                                </h3>
                                <p className="text-sm text-muted-foreground">
                                    Add favorite items to cart with one click
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-border">
                        <CardContent className="flex items-start gap-3 p-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                                <Trash2 className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <h3 className="mb-1 font-semibold text-foreground">
                                    Easy Management
                                </h3>
                                <p className="text-sm text-muted-foreground">
                                    Remove items anytime you change your mind
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div className="space-y-4">
                    {wishlistProducts.length === 0 ? (
                        <Card>
                            <CardContent className="space-y-6 p-8 text-center">
                                <div className="relative">
                                    <div className="animate-fade-in mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-muted">
                                        <Heart className="h-12 w-12 text-muted-foreground" />
                                    </div>
                                </div>

                                <div className="animate-fade-in space-y-2">
                                    <h1 className="text-3xl font-bold text-foreground">
                                        No Favorites Yet
                                    </h1>
                                    <p className="text-muted-foreground">
                                        Start adding products to your favorites
                                        and they'll appear here. Find your
                                        perfect items and save them for later!
                                    </p>
                                </div>

                                <Card className="border-none bg-muted/50">
                                    <CardContent className="space-y-2 p-4 text-left text-sm">
                                        <p className="font-medium text-foreground">
                                            Why use favorites?
                                        </p>
                                        <ul className="list-inside list-disc space-y-1 text-muted-foreground">
                                            <li>
                                                Save items you love for later
                                            </li>
                                            <li>
                                                Track price changes on products
                                            </li>
                                            <li>
                                                Quick access to your wishlist
                                            </li>
                                            <li>
                                                Share your favorites with
                                                friends
                                            </li>
                                        </ul>
                                    </CardContent>
                                </Card>

                                <div className="space-y-3 pt-4">
                                    <Link
                                        href={shop.index()}
                                        className="group w-full gap-2"
                                    >
                                        <ShoppingBag className="h-4 w-4" />
                                        Start Shopping
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>
                    ) : (
                        <>
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {wishlistProducts.map((product) => (
                                    <ProductCard
                                        key={product.id}
                                        product={product}
                                        onAddToCart={addToCart}
                                        loading={loading}
                                    />
                                ))}
                            </div>
                            {hasMore && (
                                <div className="flex justify-center pt-6">
                                    <Button
                                        variant="outline"
                                        size="lg"
                                        onClick={loadMore}
                                        className="min-w-[200px] cursor-pointer"
                                    >
                                        Load More Orders
                                    </Button>
                                </div>
                            )}
                        </>
                    )}
                </div>
            </Container>
        </AppLayout>
    );
}
