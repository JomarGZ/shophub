import shop from '@/routes/shop';
import { Link } from '@inertiajs/react';
import { Heart, ShoppingBag } from 'lucide-react';
import { Card, CardContent } from '../ui/card';

export function WishlistEmptyState() {
    return (
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
                        Start adding products to your favorites and they'll
                        appear here. Find your perfect items and save them for
                        later!
                    </p>
                </div>

                <Card className="border-none bg-muted/50">
                    <CardContent className="space-y-2 p-4 text-left text-sm">
                        <p className="font-medium text-foreground">
                            Why use favorites?
                        </p>
                        <ul className="list-inside list-disc space-y-1 text-muted-foreground">
                            <li>Save items you love for later</li>
                            <li>Track price changes on products</li>
                            <li>Quick access to your wishlist</li>
                            <li>Share your favorites with friends</li>
                        </ul>
                    </CardContent>
                </Card>

                <div className="space-y-3 pt-4">
                    <Link href={shop.index()} className="group w-full gap-2">
                        <ShoppingBag className="h-4 w-4" />
                        Start Shopping
                    </Link>
                </div>
            </CardContent>
        </Card>
    );
}
