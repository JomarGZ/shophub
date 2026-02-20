import { Heart, ShoppingBag, Trash2 } from 'lucide-react';
import { Card, CardContent } from '../ui/card';

export function WishlistFeatureCards() {
    return (
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
    );
}
