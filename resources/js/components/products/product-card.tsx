import { show } from '@/actions/App/Http/Controllers/ShopController';
import { Product } from '@/types';
import { Link } from '@inertiajs/react';
import { ShoppingCart, Star } from 'lucide-react';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Card, CardContent, CardFooter } from '../ui/card';

interface ProductCardProps {
    product: Product;
    onAddToCart?: (product: Product) => void;
}
export function ProductCard({ product, onAddToCart }: ProductCardProps) {
    return (
        <Card className="group hover:shadow-hover overflow-hidden border-border transition-all duration-300">
            <Link href={show({ slug: product.slug })}>
                <div className="aspect-square overflow-hidden bg-muted">
                    <img
                        src={product.image_url}
                        alt={product.name}
                        className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                    />
                </div>
            </Link>

            <CardContent className="space-y-2 p-4">
                <div className="flex items-start justify-between gap-2">
                    <Link href={show({ slug: product.slug })}>
                        <h3 className="line-clamp-2 font-semibold text-foreground transition-colors group-hover:text-primary">
                            {product.name}
                        </h3>
                    </Link>
                    <Badge variant="secondary" className="shrink-0">
                        {product.category.name}
                    </Badge>
                </div>

                <div className="flex items-center gap-1">
                    <Star className="h-4 w-4 fill-primary text-primary" />
                    <span className="text-sm font-medium text-foreground">
                        {product.rating}
                    </span>
                    <span className="ml-1 text-sm text-muted-foreground">
                        ({Math.floor(Math.random() * 100 + 20)} reviews)
                    </span>
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
                    className="w-full bg-primary text-primary-foreground hover:bg-primary/90"
                >
                    <ShoppingCart className="mr-2 h-4 w-4" />
                    Add to Cart
                </Button>
            </CardFooter>
        </Card>
    );
}
