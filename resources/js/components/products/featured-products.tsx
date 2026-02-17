import { useAddToCart } from '@/hooks/use-add-to-cart';
import { cn } from '@/lib/utils';
import { Product } from '@/types';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { Container } from '../container';
import { Button } from '../ui/button';
import { ProductCard } from './product-card';
interface FeatureProductsProps {
    featuredProducts: any[];
    className?: string;
}
export function FeaturedProducts({
    featuredProducts,
    className,
}: FeatureProductsProps) {
    const { addToCart, loading } = useAddToCart();
    const handleAddToCart = (product: Product) => {
        const options = {
            except: ['featured_products'],
        };
        addToCart(product, undefined, options);
    };
    return (
        <Container as="section" className={cn('py-16', className)}>
            <div className="mb-8 flex items-center justify-between">
                <h2 className="text-4xl font-bold text-foreground">
                    Featured Products
                </h2>
                <Link href="/shop">
                    <Button variant="outline" className="cursor-pointer">
                        View All <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                </Link>
            </div>

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {featuredProducts.map((product) => (
                    <ProductCard
                        key={product.id}
                        loading={loading}
                        product={product}
                        onAddToCart={handleAddToCart}
                        isFavorite={product.is_favorited}
                    />
                ))}
            </div>
        </Container>
    );
}
