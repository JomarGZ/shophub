import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { toast } from 'sonner';
import { Button } from '../ui/button';
import { ProductCard } from './product-card';

interface FeatureProductsProps {
    featuredProducts: any[];
}
export function FeaturedProducts({ featuredProducts }: FeatureProductsProps) {
    const handleAddToCart = (Product: any) => {
        toast.success(`${Product.name} added to cart!`);
    };
    return (
        <>
            <div className="mb-8 flex items-center justify-between">
                <h2 className="text-4xl font-bold text-foreground">
                    Featured Products
                </h2>
                <Link href="/shop">
                    <Button variant="outline">
                        View All <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                </Link>
            </div>

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {featuredProducts.map((product) => (
                    <ProductCard
                        key={product.id}
                        product={product}
                        onAddToCart={handleAddToCart}
                    />
                ))}
            </div>
        </>
    );
}
