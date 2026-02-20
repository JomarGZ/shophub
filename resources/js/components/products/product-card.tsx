import { show } from '@/actions/App/Http/Controllers/ShopController';
import { formatRatingCount } from '@/lib/utils';
import { Product, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Heart, ShoppingCart } from 'lucide-react';
import { AverageRatingStars } from '../average-rating-stars';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Card, CardContent, CardFooter } from '../ui/card';

interface ProductCardProps {
    product: Product;
    onAddToCart?: (product: Product) => void;
    loading: boolean;
    isFavorite?: boolean;
}
export function ProductCard({
    product,
    onAddToCart,
    isFavorite,
    loading,
}: ProductCardProps) {
    const { auth } = usePage<SharedData>().props;
    const rating = Number(product.average_rating ?? 0);
    const averageRating = Number.isFinite(rating)
        ? (Math.round(rating * 10) / 10).toFixed(1)
        : '0.0';
    const ratingsCount = Number(product.ratings_count ?? 0);
    return (
        <Card className="group hover:shadow-hover overflow-hidden border-border transition-all duration-300">
            <Link href={show(product.slug)}>
                <div className="relative aspect-square overflow-hidden bg-muted">
                    <img
                        src={product.image_url}
                        alt={product.name}
                        className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                    />
                    {isFavorite && (
                        <div className="absolute top-2 right-2">
                            <Heart className="h-5 w-5 fill-red-500 text-red-500 transition-all" />
                        </div>
                    )}
                </div>
            </Link>

            <CardContent className="space-y-2 p-4">
                <div className="flex items-start justify-between gap-2">
                    <Link href={show(product.id)}>
                        <h3 className="line-clamp-2 font-semibold text-foreground transition-colors group-hover:text-primary">
                            {product.name}
                        </h3>
                    </Link>
                    {product.category?.name && (
                        <Badge variant="secondary" className="shrink-0">
                            {product.category?.name}
                        </Badge>
                    )}
                </div>

                <div className="flex items-center gap-1">
                    <AverageRatingStars rating={rating} size="sm" />
                    {ratingsCount > 0 ? (
                        <>
                            <span className="text-sm font-medium text-foreground">
                                {averageRating}
                            </span>
                            <span className="ml-1 text-sm text-muted-foreground">
                                {`(${formatRatingCount(ratingsCount)})`}
                            </span>
                        </>
                    ) : (
                        <span className="text-sm text-muted-foreground">
                            No ratings yet
                        </span>
                    )}
                </div>

                <div className="flex items-center justify-between">
                    <span className="text-2xl font-bold text-foreground">
                        ${product.price}
                    </span>
                    <span className="text-sm text-muted-foreground">
                        {product.stock} in stock
                    </span>
                </div>
            </CardContent>

            <CardFooter className="p-4 pt-0">
                <Button
                    onClick={() => onAddToCart?.(product)}
                    disabled={Number(product.stock) === 0 || loading}
                    className={`w-full cursor-pointer ${Number(product.stock) === 0 ? 'bg-primary/60' : 'bg-primary'} text-primary-foreground hover:bg-primary/90`}
                >
                    <ShoppingCart className="mr-2 h-4 w-4" />
                    {Number(product.stock) === 0
                        ? 'Out of Stock'
                        : 'Add to Cart'}
                </Button>
            </CardFooter>
        </Card>
    );
}
