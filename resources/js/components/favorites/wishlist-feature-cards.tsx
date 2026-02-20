import { Heart, ShoppingBag, Trash2 } from 'lucide-react';
import { WishlistFeatureCard } from './wishlist-feature-card';

export function WishlistFeatureCards() {
    return (
        <div className="animate-fade-in mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
            <WishlistFeatureCard
                icon={<Heart className="h-5 w-5 text-primary" />}
                title="Save for Later"
                description="h-5 w-5 text-primary"
            />

            <WishlistFeatureCard
                icon={<ShoppingBag className="h-5 w-5 text-primary" />}
                title="Quick Add to Cart"
                description="Add favorite items to cart with one click"
            />

            <WishlistFeatureCard
                icon={<Trash2 className="h-5 w-5 text-primary" />}
                title="Easy Management"
                description="Remove items anytime you change your mind"
            />
        </div>
    );
}
