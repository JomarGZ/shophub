import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import orders from '@/routes/orders';
import shop from '@/routes/shop';
import { Link } from '@inertiajs/react';
import { ArrowRight, CheckCircle, Package, ShoppingBag } from 'lucide-react';

type Order = {
    id: string;
    shipping_fee: number;
    subtotal: number;
    total: number;
};

type Items = {
    id: number;
    product_name: string;
    quantity: number;
    line_total: number;
    product_price: number;
};
interface ProcessingProps {
    order: Order;
    items: Items[];
}
export default function Processing({ order, items }: ProcessingProps) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-background via-background to-primary/5 px-4 py-12">
            <div className="animate-fade-in mx-auto max-w-3xl space-y-8">
                {/* Success Header */}
                <div className="space-y-4 text-center">
                    <div className="relative inline-block">
                        <div className="animate-scale-in mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">
                            <CheckCircle className="h-12 w-12 text-primary" />
                        </div>
                        <div className="absolute -top-1 -right-1 h-6 w-6 animate-ping rounded-full bg-primary" />
                    </div>

                    <div className="space-y-2">
                        <h1 className="text-4xl font-bold text-foreground">
                            Thank You for Your Order!
                        </h1>
                        <p className="mx-auto max-w-2xl text-lg text-muted-foreground">
                            Your order has been received and your payment is
                            currently being verified. You will receive an email
                            once payment is confirmed.
                        </p>
                    </div>

                    <div className="inline-flex items-center gap-2 rounded-full bg-muted/50 px-4 py-2">
                        <Package className="h-4 w-4 text-primary" />
                        <span className="text-sm font-medium text-foreground">
                            Order #{order.id}
                        </span>
                    </div>
                </div>

                {/* Order Summary Card */}
                <Card className="shadow-[var(--shadow-card)]">
                    <CardHeader className="border-b border-border bg-muted/30">
                        <CardTitle className="flex items-center gap-2 text-xl">
                            <ShoppingBag className="h-5 w-5 text-primary" />
                            Order Summary
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-6 p-6">
                        {/* Items List */}
                        <div className="space-y-4">
                            {items.map((item) => (
                                <div
                                    key={item.id}
                                    className="flex items-start justify-between gap-4"
                                >
                                    <div className="flex-1">
                                        <h3 className="font-medium text-foreground">
                                            {item.product_name}
                                        </h3>
                                        <p className="text-sm text-muted-foreground">
                                            Quantity: {item.quantity}
                                        </p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-semibold text-foreground">
                                            ${item.line_total}
                                        </p>
                                        <p className="text-sm text-muted-foreground">
                                            ${item.product_price} each
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <Separator />

                        {/* Price Breakdown */}
                        <div className="space-y-3">
                            <div className="flex justify-between text-sm">
                                <span className="text-muted-foreground">
                                    Subtotal
                                </span>
                                <span className="font-medium text-foreground">
                                    ${order.subtotal}
                                </span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-muted-foreground">
                                    Shipping
                                </span>
                                <span className="font-medium text-foreground">
                                    ${order.shipping_fee}
                                </span>
                            </div>

                            <Separator />

                            <div className="flex justify-between text-lg font-bold">
                                <span className="text-foreground">Total</span>
                                <span className="text-primary">
                                    ${order.total}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Next Steps Card */}
                <Card className="border-primary/20 bg-muted/30 shadow-[var(--shadow-soft)]">
                    <CardContent className="space-y-3 p-6">
                        <h3 className="flex items-center gap-2 font-semibold text-foreground">
                            <Package className="h-5 w-5 text-primary" />
                            What's Next?
                        </h3>
                        <ul className="space-y-2 text-sm text-muted-foreground">
                            <li className="flex items-start gap-2">
                                <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-primary" />
                                <span>
                                    Order confirmation has been sent to your
                                    email
                                </span>
                            </li>
                            <li className="flex items-start gap-2">
                                <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-primary" />
                                <span>
                                    You can track your order status in the Order
                                    History page
                                </span>
                            </li>
                            <li className="flex items-start gap-2">
                                <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-primary" />
                                <span>
                                    We'll notify you once your order ships
                                </span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>

                {/* Action Buttons */}
                <div className="flex flex-col gap-3 sm:flex-row">
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
            </div>
        </div>
    );
}
