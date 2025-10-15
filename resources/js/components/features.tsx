import { RefreshCw, Shield, ShoppingBag, Truck } from 'lucide-react';

export function Features() {
    return (
        <div className="grid grid-cols-1 gap-8 md:grid-cols-4">
            <div className="text-center">
                <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <Truck className="h-8 w-8 text-primary" />
                </div>
                <h3 className="mb-2 text-lg font-semibold text-foreground">
                    Free Shipping
                </h3>
                <p className="text-sm text-muted-foreground">
                    On orders over $100
                </p>
            </div>

            <div className="text-center">
                <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <Shield className="h-8 w-8 text-primary" />
                </div>
                <h3 className="mb-2 text-lg font-semibold text-foreground">
                    Secure Payment
                </h3>
                <p className="text-sm text-muted-foreground">
                    100% secure transactions
                </p>
            </div>

            <div className="text-center">
                <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <RefreshCw className="h-8 w-8 text-primary" />
                </div>
                <h3 className="mb-2 text-lg font-semibold text-foreground">
                    Easy Returns
                </h3>
                <p className="text-sm text-muted-foreground">
                    30-day return policy
                </p>
            </div>

            <div className="text-center">
                <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <ShoppingBag className="h-8 w-8 text-primary" />
                </div>
                <h3 className="mb-2 text-lg font-semibold text-foreground">
                    Quality Products
                </h3>
                <p className="text-sm text-muted-foreground">
                    Carefully curated selection
                </p>
            </div>
        </div>
    );
}
