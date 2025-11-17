import { Card, CardContent } from '@/components/ui/card';
import orders from '@/routes/orders';
import shop from '@/routes/shop';
import { Link } from '@inertiajs/react';
import { ArrowRight, CheckCircle, Package } from 'lucide-react';

export default function Success() {
    return (
        <div className="flex min-h-screen items-center justify-center">
            <Card className="animate-scale-in w-full max-w-md border-2 shadow-[var(--shadow-hover)]">
                <CardContent className="space-y-6 p-8 text-center">
                    <div className="relative">
                        <div className="animate-fade-in mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">
                            <CheckCircle className="h-12 w-12 text-primary" />
                        </div>
                        <div className="absolute -top-1 -right-1 h-6 w-6 animate-ping rounded-full bg-primary" />
                    </div>

                    <div className="animate-fade-in space-y-2">
                        <h1 className="text-3xl font-bold text-foreground">
                            Payment Successful!
                        </h1>
                        <p className="text-muted-foreground">
                            Thank you for your order. Your payment has been
                            processed successfully.
                        </p>
                    </div>

                    <Card className="border-none bg-muted/50">
                        <CardContent className="space-y-2 p-4">
                            <div className="flex items-center gap-2 text-sm">
                                <Package className="h-4 w-4 text-primary" />
                                <span className="text-muted-foreground">
                                    Order confirmation has been sent to your
                                    email
                                </span>
                            </div>
                            <div className="text-sm text-muted-foreground">
                                You can track your order in the Order History
                                page
                            </div>
                        </CardContent>
                    </Card>

                    <div className="space-y-3 pt-4">
                        <Link
                            href={orders.index().url} // replace with your order page route
                            className="group inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-4 py-3 text-lg font-medium text-white shadow transition hover:bg-primary/90"
                        >
                            View Order History
                            <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </Link>

                        <Link
                            href={shop.index().url} // replace with your shop page route
                            className="inline-flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-3 text-lg font-medium transition hover:bg-gray-100"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
