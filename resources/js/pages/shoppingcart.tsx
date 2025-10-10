import { Container } from '@/components/container';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CartItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Minus, Plus, X } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '#',
    },
];

interface ShoppingCartProps {
    cart_items: CartItem[];
}

export default function ShoppingCart({ cart_items }: ShoppingCartProps) {
    const updateQuantity = (id: string, delta: number) => {
        console.log('updated quantity');
    };
    const removeItem = (id: string) => {
        console.log('removed item');
    };

    const subtotal = cart_items.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0,
    );
    const shipping = subtotal > 100 ? 0 : 9.99;
    const total = subtotal + shipping;
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Shop" />
            <Container className="bg-background px-4 py-8">
                <h1 className="mb-8 text-4xl font-bold text-foreground">
                    Shopping Cart
                </h1>
                <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div className="space-y-4 lg:col-span-2">
                        {cart_items.map((item) => (
                            <Card key={item.id} className="shadow-card">
                                <CardContent className="p-4">
                                    <div className="flex gap-4">
                                        <Link href={`#`}>
                                            <img
                                                src={item.image}
                                                alt={item.name}
                                                className="h-24 w-24 rounded-lg object-cover"
                                            />
                                        </Link>

                                        <div className="flex-1 space-y-2">
                                            <div className="flex items-start justify-between">
                                                <div>
                                                    <Link href={`#`}>
                                                        <h3 className="font-semibold text-foreground transition-colors hover:text-primary">
                                                            {item.name}
                                                        </h3>
                                                    </Link>
                                                    <p className="text-sm text-muted-foreground">
                                                        {item.category}
                                                    </p>
                                                </div>
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    onClick={() =>
                                                        removeItem(item.id)
                                                    }
                                                    className="text-muted-foreground hover:text-destructive"
                                                >
                                                    <X className="h-5 w-5" />
                                                </Button>
                                            </div>

                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center rounded-lg border border-border">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="h-8 w-8"
                                                        onClick={() =>
                                                            updateQuantity(
                                                                item.id,
                                                                -1,
                                                            )
                                                        }
                                                    >
                                                        <Minus className="h-4 w-4" />
                                                    </Button>
                                                    <span className="w-10 text-center font-semibold">
                                                        {item.quantity}
                                                    </span>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="h-8 w-8"
                                                        onClick={() =>
                                                            updateQuantity(
                                                                item.id,
                                                                1,
                                                            )
                                                        }
                                                    >
                                                        <Plus className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                                <p className="text-xl font-bold text-foreground">
                                                    $
                                                    {(
                                                        item.price *
                                                        item.quantity
                                                    ).toFixed(2)}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                    <div className="lg:col-span-1">
                        <Card className="sticky top-24 shadow-card">
                            <CardContent className="space-y-4 p-6">
                                <h2 className="text-2xl font-bold text-foreground">
                                    Order Summary
                                </h2>

                                <Separator />

                                <div className="space-y-3">
                                    <div className="flex justify-between text-foreground">
                                        <span>Subtotal</span>
                                        <span className="font-semibold">
                                            ${subtotal.toFixed(2)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-foreground">
                                        <span>Shipping</span>
                                        <span className="font-semibold">
                                            {shipping === 0
                                                ? 'FREE'
                                                : `$${shipping.toFixed(2)}`}
                                        </span>
                                    </div>
                                    {shipping === 0 && (
                                        <p className="text-sm text-primary">
                                            🎉 You qualify for free shipping!
                                        </p>
                                    )}
                                </div>

                                <Separator />

                                <div className="flex justify-between text-xl font-bold text-foreground">
                                    <span>Total</span>
                                    <span>${total.toFixed(2)}</span>
                                </div>

                                <Link href="/checkout">
                                    <Button className="h-12 w-full bg-primary text-base text-primary-foreground hover:bg-primary/90">
                                        Proceed to Checkout
                                    </Button>
                                </Link>

                                <Link href="/shop">
                                    <Button
                                        variant="outline"
                                        className="w-full"
                                    >
                                        Continue Shopping
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </Container>
        </AppLayout>
    );
}
