// resources/js/Pages/Checkout.tsx

import AddressController from '@/actions/App/Http/Controllers/AddressController';
import { Container } from '@/components/container';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Command,
    CommandEmpty,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/cart';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import {
    Banknote,
    Check,
    CreditCard,
    LoaderCircle,
    MapPin,
    Plus,
    Trash2,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { useDebounce } from 'use-debounce';
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
type City = {
    id: number;
    name: string;
};
interface OrderItem {
    name: string;
    quantity: number;
    price: number;
}

export default function Index({
    addresses,
    countries,
    cart,
    paymentMethods,
}: {
    addresses: any[];
    cart: any;
    paymentMethods: any[];
    countries: any[];
}) {
    const [showAddForm, setShowAddForm] = useState(false);
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('cod');
    const [selectedAddressId, setSelectedAddressId] = useState<string>('');
    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(false);
    const [cities, setCities] = useState<City[]>([]);
    const [open, setOpen] = useState(false);
    const controllerForm = AddressController.store.form();
    const [debounceSearch] = useDebounce(search, 500);
    const form = useForm({
        country_id: '',
        city_id: '',
        first_name: '',
        last_name: '',
        phone: '',
        street_address: '',
    });
    const { data, setData, processing, errors, post } = form;
    const fetchCities = async (query = '', country_id: number) => {
        if (!data.country_id) return;
        setLoading(true);
        try {
            const { data: res } = await axios.get('city/list', {
                params: {
                    country_id: country_id,
                    search: query,
                    limit: query ? 20 : 100,
                },
            });
            setCities(res.success && Array.isArray(res.data) ? res.data : []);
        } catch (error) {
            console.error('Error fetching cities:', error);
            setCities([]);
        } finally {
            setLoading(false);
        }
    };

    const handleSetDefault = (address) => {};
    const handleDeleteAddress = (address) => {};
    const onChange = (value: string) => {};
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
    const handleSubmit = (e) => {
        e.preventDefault();
        post(controllerForm.action, {
            preserveScroll: true,
            onSuccess: ({ props: { flash } }: any) => {
                toast.success(flash.message);
            },
        });
    };
    useEffect(() => {
        fetchCities(debounceSearch, Number(data.country_id));
    }, [debounceSearch, data.country_id]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Checkout" />
            <Container className="bg-background px-4 py-4">
                <h1 className="mb-8 text-4xl font-bold text-foreground">
                    Checkout
                </h1>
                <form onSubmit={handleSubmit} method={controllerForm.method}>
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
                                                        <Label htmlFor="first_ame">
                                                            First Name *
                                                        </Label>
                                                        <Input
                                                            id="first_ame"
                                                            type="text"
                                                            value={
                                                                data.first_name
                                                            }
                                                            onChange={(e) =>
                                                                setData(
                                                                    'first_name',
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            autoComplete="first_name"
                                                            name="first_name"
                                                        />
                                                        <InputError
                                                            message={
                                                                errors.first_name
                                                            }
                                                            className="mt-2"
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label htmlFor="last_name">
                                                            Last Name *
                                                        </Label>
                                                        <Input
                                                            id="last_name"
                                                            type="text"
                                                            value={
                                                                data.last_name
                                                            }
                                                            onChange={(e) =>
                                                                setData(
                                                                    'last_name',
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            autoComplete="last_name"
                                                            name="last_name"
                                                        />
                                                        <InputError
                                                            message={
                                                                errors.last_name
                                                            }
                                                            className="mt-2"
                                                        />
                                                    </div>
                                                </div>

                                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <Label htmlFor="country_id">
                                                            Country *
                                                        </Label>
                                                        <Select
                                                            value={
                                                                String(
                                                                    data.country_id,
                                                                ) || ''
                                                            }
                                                            onValueChange={(
                                                                value,
                                                            ) =>
                                                                setData(
                                                                    'country_id',
                                                                    value,
                                                                )
                                                            }
                                                        >
                                                            <SelectTrigger
                                                                className="w-full"
                                                                id="country_id"
                                                            >
                                                                <SelectValue placeholder="Select Country" />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                {countries.length >
                                                                    0 &&
                                                                    countries.map(
                                                                        (
                                                                            country,
                                                                        ) => (
                                                                            <SelectItem
                                                                                key={
                                                                                    country.id
                                                                                }
                                                                                value={String(
                                                                                    country.id,
                                                                                )}
                                                                            >
                                                                                {
                                                                                    country.name
                                                                                }
                                                                            </SelectItem>
                                                                        ),
                                                                    )}
                                                            </SelectContent>
                                                        </Select>
                                                        <InputError
                                                            message={
                                                                errors.country_id
                                                            }
                                                            className="mt-2"
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label>City *</Label>
                                                        <Popover
                                                            open={open}
                                                            onOpenChange={
                                                                setOpen
                                                            }
                                                        >
                                                            <PopoverTrigger
                                                                asChild
                                                            >
                                                                <Button
                                                                    variant="outline"
                                                                    role="combobox"
                                                                    className="w-full justify-between"
                                                                >
                                                                    {data.city_id
                                                                        ? cities.find(
                                                                              (
                                                                                  city,
                                                                              ) =>
                                                                                  city.id ===
                                                                                  Number(
                                                                                      data.city_id,
                                                                                  ),
                                                                          )
                                                                              ?.name ||
                                                                          'Select city'
                                                                        : 'Select city'}
                                                                </Button>
                                                            </PopoverTrigger>
                                                            <PopoverContent className="w-[300px] p-0">
                                                                <Command
                                                                    shouldFilter={
                                                                        false
                                                                    }
                                                                >
                                                                    <CommandInput
                                                                        placeholder="Search city..."
                                                                        value={
                                                                            search
                                                                        }
                                                                        onValueChange={
                                                                            setSearch
                                                                        }
                                                                    />
                                                                    <CommandList>
                                                                        <CommandEmpty>
                                                                            {loading
                                                                                ? 'Loading...'
                                                                                : 'No cities found.'}
                                                                        </CommandEmpty>
                                                                        {!loading &&
                                                                            cities.map(
                                                                                (
                                                                                    city,
                                                                                ) => (
                                                                                    <CommandItem
                                                                                        key={
                                                                                            city.id
                                                                                        }
                                                                                        value={String(
                                                                                            city.id,
                                                                                        )}
                                                                                        onSelect={() => {
                                                                                            setData(
                                                                                                'city_id',
                                                                                                String(
                                                                                                    city.id,
                                                                                                ),
                                                                                            );
                                                                                            setOpen(
                                                                                                false,
                                                                                            );
                                                                                        }}
                                                                                    >
                                                                                        {
                                                                                            city.name
                                                                                        }
                                                                                    </CommandItem>
                                                                                ),
                                                                            )}
                                                                    </CommandList>
                                                                </Command>
                                                            </PopoverContent>
                                                        </Popover>
                                                        <InputError
                                                            message={
                                                                errors.city_id
                                                            }
                                                            className="mt-2"
                                                        />
                                                    </div>
                                                </div>
                                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <Label htmlFor="phone">
                                                            Phone *
                                                        </Label>
                                                        <Input
                                                            id="phone"
                                                            type="tel"
                                                            value={data.phone}
                                                            onChange={(e) =>
                                                                setData(
                                                                    'phone',
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            autoComplete="tel"
                                                            name="phone"
                                                        />
                                                        <InputError
                                                            message={
                                                                errors.phone
                                                            }
                                                            className="mt-2"
                                                        />
                                                    </div>
                                                    <div>
                                                        <Label htmlFor="street_address">
                                                            Street Address *
                                                        </Label>
                                                        <Input
                                                            id="street_address"
                                                            type="text"
                                                            value={
                                                                data.street_address
                                                            }
                                                            onChange={(e) =>
                                                                setData(
                                                                    'street_address',
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            autoComplete="street_address"
                                                            name="street_address"
                                                        />
                                                        <InputError
                                                            message={
                                                                errors.street_address
                                                            }
                                                        />
                                                    </div>
                                                </div>

                                                <div className="flex gap-2">
                                                    <Button
                                                        type="submit"
                                                        disabled={processing}
                                                        className="bg-primary text-primary-foreground hover:bg-primary/90"
                                                    >
                                                        {processing && (
                                                            <LoaderCircle className="h-4 w-4 animate-spin" />
                                                        )}
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
