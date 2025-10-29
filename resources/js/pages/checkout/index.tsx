// resources/js/Pages/Checkout.tsx

import { Container } from '@/components/container';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/cart';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import {
    Banknote,
    Check,
    CreditCard,
    MapPin,
    Plus,
    Trash2,
} from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cart',
        href: index().url,
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

export default function Index({
    addresses,
    cart,
    paymentMethods,
}: {
    addresses: any[];
    cart: any;
}) {
    const [showAddForm, setShowAddForm] = useState(false);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('cod');
    const [selectedAddressId, setSelectedAddressId] = useState<string>('');
    const [newAddress, setNewAddress] = useState({
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        street: '',
        city: '',
        zipCode: '',
    });

    const { post } = useForm({});

    const handleSubmit = (e) => {
        e.preventDefault();
        toast.success('Order placed successfully');
    };

    const handleAddAddress = () => {
        toast.success('Address added successfully');
    };

    const handleDeleteAddress = (addressId) => {
        toast.success('Address deleted');
    };

    const handleSetDefault = (addressId) => {
        toast.success('Default address updated');
    };

    const orderItems = [
        { name: 'Wireless Headphones', price: 79.99, quantity: 2 },
        { name: 'Running Shoes', price: 129.99, quantity: 1 },
    ];

    const subtotal = orderItems.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0,
    );
    const shipping = 0;
    const total = subtotal + shipping;

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
                            {/* Address Management */}
                            <Card className="shadow-card">
                                <CardHeader className="border-b border-secondary/20">
                                    <div className="flex items-center justify-between">
                                        <CardTitle className="flex items-center gap-2 text-secondary">
                                            <MapPin className="h-5 w-5" />
                                            Delivery Address
                                        </CardTitle>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={() =>
                                                setShowAddForm(!showAddForm)
                                            }
                                            className="flex items-center gap-2"
                                        >
                                            <Plus className="h-4 w-4" />
                                            Add Address
                                        </Button>
                                    </div>
                                </CardHeader>
                                <CardContent className="space-y-4 p-6">
                                    {/* Saved Addresses */}
                                    {addresses.length > 0 && (
                                        <RadioGroup
                                            value={selectedAddressId}
                                            onValueChange={setSelectedAddressId}
                                        >
                                            <div className="space-y-3">
                                                {addresses.map((address) => (
                                                    <div
                                                        key={address.id}
                                                        className={`relative rounded-lg border p-4 transition-all ${
                                                            selectedAddressId ===
                                                            address.id
                                                                ? 'border-primary bg-primary/5'
                                                                : 'border-border hover:border-primary/50'
                                                        }`}
                                                    >
                                                        <div className="flex items-start gap-3">
                                                            <RadioGroupItem
                                                                value={
                                                                    address.id
                                                                }
                                                                id={address.id}
                                                                className="mt-1"
                                                            />
                                                            <div className="flex-1">
                                                                <Label
                                                                    htmlFor={
                                                                        address.id
                                                                    }
                                                                    className="cursor-pointer"
                                                                >
                                                                    <div className="mb-2 flex items-center gap-2">
                                                                        <span className="font-semibold text-foreground">
                                                                            {
                                                                                address.firstName
                                                                            }{' '}
                                                                            {
                                                                                address.lastName
                                                                            }
                                                                        </span>
                                                                        {address.isDefault && (
                                                                            <span className="rounded-full bg-primary px-2 py-0.5 text-xs text-primary-foreground">
                                                                                Default
                                                                            </span>
                                                                        )}
                                                                    </div>
                                                                    <p className="text-sm text-muted-foreground">
                                                                        {
                                                                            address.street
                                                                        }
                                                                    </p>
                                                                    <p className="text-sm text-muted-foreground">
                                                                        {
                                                                            address.city
                                                                        }
                                                                        ,{' '}
                                                                        {
                                                                            address.zipCode
                                                                        }
                                                                    </p>
                                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                                        {
                                                                            address.phone
                                                                        }
                                                                    </p>
                                                                </Label>
                                                            </div>
                                                            <div className="flex items-center gap-2">
                                                                {!address.isDefault && (
                                                                    <Button
                                                                        type="button"
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        onClick={() =>
                                                                            handleSetDefault(
                                                                                address.id,
                                                                            )
                                                                        }
                                                                        className="h-8 text-xs"
                                                                    >
                                                                        <Check className="mr-1 h-3 w-3" />
                                                                        Set
                                                                        Default
                                                                    </Button>
                                                                )}
                                                                <Button
                                                                    type="button"
                                                                    variant="ghost"
                                                                    size="sm"
                                                                    onClick={() =>
                                                                        handleDeleteAddress(
                                                                            address.id,
                                                                        )
                                                                    }
                                                                    className="h-8 w-8 p-0 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                >
                                                                    <Trash2 className="h-4 w-4" />
                                                                </Button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        </RadioGroup>
                                    )}

                                    {/* Add New Address Form */}
                                    {showAddForm && (
                                        <div className="mt-4 rounded-lg border border-border bg-muted/30 p-4">
                                            <h3 className="mb-4 font-semibold text-foreground">
                                                New Address
                                            </h3>
                                            <div className="space-y-4">
                                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <Label htmlFor="newFirstName">
                                                            First Name *
                                                        </Label>
                                                        <Input
                                                            id="newFirstName"
                                                            value={
                                                                newAddress.firstName
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    firstName:
                                                                        e.target
                                                                            .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label htmlFor="newLastName">
                                                            Last Name
                                                        </Label>
                                                        <Input
                                                            id="newLastName"
                                                            value={
                                                                newAddress.lastName
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    lastName:
                                                                        e.target
                                                                            .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                </div>

                                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <Label htmlFor="newEmail">
                                                            Email
                                                        </Label>
                                                        <Input
                                                            id="newEmail"
                                                            type="email"
                                                            value={
                                                                newAddress.email
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    email: e
                                                                        .target
                                                                        .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label htmlFor="newPhone">
                                                            Phone
                                                        </Label>
                                                        <Input
                                                            id="newPhone"
                                                            type="tel"
                                                            value={
                                                                newAddress.phone
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    phone: e
                                                                        .target
                                                                        .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                </div>

                                                <div>
                                                    <Label htmlFor="newStreet">
                                                        Street Address *
                                                    </Label>
                                                    <Input
                                                        id="newStreet"
                                                        value={
                                                            newAddress.street
                                                        }
                                                        onChange={(e) =>
                                                            setNewAddress({
                                                                ...newAddress,
                                                                street: e.target
                                                                    .value,
                                                            })
                                                        }
                                                    />
                                                </div>

                                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <Label htmlFor="newCity">
                                                            City *
                                                        </Label>
                                                        <Input
                                                            id="newCity"
                                                            value={
                                                                newAddress.city
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    city: e
                                                                        .target
                                                                        .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label htmlFor="newZipCode">
                                                            ZIP Code *
                                                        </Label>
                                                        <Input
                                                            id="newZipCode"
                                                            value={
                                                                newAddress.zipCode
                                                            }
                                                            onChange={(e) =>
                                                                setNewAddress({
                                                                    ...newAddress,
                                                                    zipCode:
                                                                        e.target
                                                                            .value,
                                                                })
                                                            }
                                                        />
                                                    </div>
                                                </div>

                                                <div className="flex gap-2">
                                                    <Button
                                                        type="button"
                                                        onClick={
                                                            handleAddAddress
                                                        }
                                                        className="bg-primary text-primary-foreground hover:bg-primary/90"
                                                    >
                                                        Save Address
                                                    </Button>
                                                    <Button
                                                        type="button"
                                                        variant="outline"
                                                        onClick={() =>
                                                            setShowAddForm(
                                                                false,
                                                            )
                                                        }
                                                    >
                                                        Cancel
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    )}

                                    {addresses.length === 0 && !showAddForm && (
                                        <div className="py-8 text-center text-muted-foreground">
                                            <MapPin className="mx-auto mb-3 h-12 w-12 opacity-50" />
                                            <p>No saved addresses yet</p>
                                            <p className="text-sm">
                                                Add an address to continue with
                                                checkout
                                            </p>
                                        </div>
                                    )}
                                </CardContent>
                            </Card>

                            {/* Payment Method */}
                            <Card className="shadow-card">
                                <CardHeader className="border-b border-secondary/20">
                                    <CardTitle className="flex items-center gap-2 text-secondary">
                                        <CreditCard className="h-5 w-5" />
                                        Payment Method
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="p-6">
                                    <RadioGroup
                                        value={selectedPaymentMethod}
                                        onValueChange={(value) =>
                                            setSelectedPaymentMethod(
                                                value as 'cod' | 'paypal',
                                            )
                                        }
                                    >
                                        <div className="space-y-3">
                                            {/* Cash on Delivery */}
                                            <div
                                                className={`relative rounded-lg border p-4 transition-all ${
                                                    selectedPaymentMethod ===
                                                    'cod'
                                                        ? 'border-primary bg-primary/5'
                                                        : 'border-border hover:border-primary/50'
                                                }`}
                                            >
                                                <div className="flex items-center gap-3">
                                                    <RadioGroupItem
                                                        value="cod"
                                                        id="cod"
                                                    />
                                                    <Label
                                                        htmlFor="cod"
                                                        className="flex flex-1 cursor-pointer items-center gap-3"
                                                    >
                                                        <Banknote className="h-5 w-5 text-primary" />
                                                        <div>
                                                            <div className="font-semibold text-foreground">
                                                                Cash on Delivery
                                                            </div>
                                                            <p className="text-sm text-muted-foreground">
                                                                Pay when you
                                                                receive your
                                                                order
                                                            </p>
                                                        </div>
                                                    </Label>
                                                </div>
                                            </div>

                                            {/* PayPal */}
                                            <div
                                                className={`relative rounded-lg border p-4 transition-all ${
                                                    selectedPaymentMethod ===
                                                    'paypal'
                                                        ? 'border-primary bg-primary/5'
                                                        : 'border-border hover:border-primary/50'
                                                }`}
                                            >
                                                <div className="flex items-center gap-3">
                                                    <RadioGroupItem
                                                        value="paypal"
                                                        id="paypal"
                                                    />
                                                    <Label
                                                        htmlFor="paypal"
                                                        className="flex flex-1 cursor-pointer items-center gap-3"
                                                    >
                                                        <CreditCard className="h-5 w-5 text-primary" />
                                                        <div>
                                                            <div className="font-semibold text-foreground">
                                                                PayPal
                                                            </div>
                                                            <p className="text-sm text-muted-foreground">
                                                                Pay securely
                                                                with PayPal
                                                            </p>
                                                        </div>
                                                    </Label>
                                                </div>
                                            </div>
                                        </div>
                                    </RadioGroup>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Order Summary */}
                        <div className="lg:col-span-1">
                            <Card className="sticky top-24 shadow-card">
                                <CardHeader className="border-b border-secondary/20">
                                    <CardTitle className="text-secondary">
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
                                    >
                                        Place Order
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
