import { Alert, AlertDescription } from '@/components/ui/alert';
import { Card, CardContent } from '@/components/ui/card';
import cart from '@/routes/cart';
import shop from '@/routes/shop';
import { Link } from '@inertiajs/react';
import { AlertCircle, ShoppingCart, Store } from 'lucide-react';

export default function Cancelled() {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-background via-background to-muted/30 p-4">
            <Card className="animate-scale-in w-full max-w-md shadow-[var(--shadow-hover)]">
                <CardContent className="space-y-6 p-8 text-center">
                    <div className="relative">
                        <div className="animate-fade-in mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-muted">
                            <AlertCircle className="h-12 w-12 text-muted-foreground" />
                        </div>
                    </div>

                    <div className="animate-fade-in space-y-2">
                        <h1 className="text-3xl font-bold text-foreground">
                            Payment Cancelled
                        </h1>
                        <p className="text-muted-foreground">
                            Your payment was not completed. No charges were made
                            to your account. You can return to your cart or
                            continue shopping.
                        </p>
                    </div>

                    <Alert className="border-muted text-left">
                        <AlertCircle className="h-4 w-4 text-muted-foreground" />
                        <AlertDescription className="text-muted-foreground">
                            Your cart items have been saved. You can complete
                            your purchase anytime.
                        </AlertDescription>
                    </Alert>

                    <Card className="border-none bg-muted/50">
                        <CardContent className="space-y-2 p-4 text-left text-sm">
                            <p className="font-medium text-foreground">
                                What happens next?
                            </p>
                            <ul className="list-inside list-disc space-y-1 text-muted-foreground">
                                <li>Your items remain in your cart</li>
                                <li>You can review your order anytime</li>
                                <li>No payment was processed</li>
                                <li>Complete checkout when you're ready</li>
                            </ul>
                        </CardContent>
                    </Card>

                    <div className="space-y-3 pt-4">
                        <Link
                            href={cart.index().url}
                            className="group inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-4 py-3 text-lg font-medium text-white shadow transition hover:bg-primary/90"
                        >
                            <ShoppingCart className="h-4 w-4" />
                            Return to Cart
                        </Link>
                        <Link
                            href={shop.index().url}
                            className="inline-flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-3 text-lg font-medium transition hover:bg-gray-100"
                        >
                            <Store className="h-4 w-4" />
                            Continue Shopping
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
