// resources/js/Pages/Checkout.tsx

import { Container } from '@/components/container';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { cart } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cart',
        href: cart().url,
    },
    {
        title: 'Checkout',
        href: '#',
    },
];

interface OrderItem {
    name: string;
    quantity: number;
    price: number;
}

export default function Checkout() {
    const orderItems: OrderItem[] = [
        { name: 'Product 1', quantity: 2, price: 19.99 },
        { name: 'Product 2', quantity: 1, price: 49.99 },
    ];

    const subtotal = orderItems.reduce(
        (acc, item) => acc + item.price * item.quantity,
        0,
    );
    const total = subtotal; // Shipping is free

    const { data, setData, post, processing } = useForm({
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        address: '',
        city: '',
        zipCode: '',
        cardNumber: '',
        cardExpiry: '',
        cardCvc: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>): void => {
        const { name, value } = e.target;
        setData(name as keyof typeof data, value);
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        console.log('Form submitted', data);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Checkout" />
            <Container className="bg-background px-4 py-4">
                <h1 className="mb-8 text-4xl font-bold text-foreground">
                    Checkout
                </h1>
                <form onSubmit={handleSubmit}>
                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        {/* Checkout Form */}
                        <div className="space-y-6 lg:col-span-2">
                            {/* Shipping Information */}
                            <Card className="shadow-card">
                                <CardHeader className="border-b border-secondary/20">
                                    <CardTitle className="py-2 text-secondary">
                                        Shipping Information
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-4 p-6">
                                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <Label htmlFor="firstName">
                                                First Name
                                            </Label>
                                            <Input
                                                id="firstName"
                                                name="firstName"
                                                value={data.firstName}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="lastName">
                                                Last Name
                                            </Label>
                                            <Input
                                                id="lastName"
                                                name="lastName"
                                                value={data.lastName}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <Label htmlFor="email">Email</Label>
                                            <Input
                                                id="email"
                                                name="email"
                                                type="email"
                                                value={data.email}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="phone">Phone</Label>
                                            <Input
                                                id="phone"
                                                name="phone"
                                                type="tel"
                                                value={data.phone}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <Label htmlFor="address">
                                            Street Address
                                        </Label>
                                        <Input
                                            id="address"
                                            name="address"
                                            value={data.address}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>

                                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <Label htmlFor="city">City</Label>
                                            <Input
                                                id="city"
                                                name="city"
                                                value={data.city}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="zipCode">
                                                ZIP Code
                                            </Label>
                                            <Input
                                                id="zipCode"
                                                name="zipCode"
                                                value={data.zipCode}
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Order Summary */}
                        <div className="lg:col-span-1">
                            <Card className="sticky top-24 shadow-card">
                                <CardHeader className="border-b border-secondary/20">
                                    <CardTitle className="py-2 text-secondary">
                                        Order Summary
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-4 p-6">
                                    {/* Order Items */}
                                    <div className="space-y-3">
                                        {orderItems.map((item, index) => (
                                            <div
                                                key={index}
                                                className="flex justify-between text-sm"
                                            >
                                                <span className="text-foreground">
                                                    {item.name} x{item.quantity}
                                                </span>
                                                <span className="font-semibold">
                                                    $
                                                    {(
                                                        item.price *
                                                        item.quantity
                                                    ).toFixed(2)}
                                                </span>
                                            </div>
                                        ))}
                                    </div>

                                    <Separator />

                                    <div className="space-y-2">
                                        <div className="flex justify-between text-foreground">
                                            <span>Subtotal</span>
                                            <span className="font-semibold">
                                                ${subtotal.toFixed(2)}
                                            </span>
                                        </div>
                                        <div className="flex justify-between text-foreground">
                                            <span>Shipping</span>
                                            <span className="font-semibold text-primary">
                                                FREE
                                            </span>
                                        </div>
                                    </div>

                                    <Separator />

                                    <div className="flex justify-between text-xl font-bold text-foreground">
                                        <span>Total</span>
                                        <span>${total.toFixed(2)}</span>
                                    </div>

                                    <Button
                                        type="submit"
                                        className="h-12 w-full bg-primary text-base text-primary-foreground hover:bg-primary/90"
                                        disabled={processing}
                                    >
                                        {processing
                                            ? 'Processing...'
                                            : 'Place Order'}
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </form>
            </Container>
        </AppLayout>
    );
}
