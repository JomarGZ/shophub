import { Container } from '@/components/container';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index as cartIndex } from '@/routes/cart';
import { destroy, update } from '@/routes/cart/item';
import { index } from '@/routes/checkout';
import { BreadcrumbItem, CartItem, PaginatedResponse } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Minus, Plus, Search, ShoppingBag, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { useDebounce } from 'use-debounce';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '#',
    },
];

interface ShoppingCartProps {
    cart_items: PaginatedResponse<CartItem>;
    order_summary: {
        sub_total: number;
        shipping_fee: number;
    };
}

function emptyCart() {
    return (
        <div className="flex flex-1 items-center justify-center">
            <div className="space-y-4 text-center">
                <ShoppingBag className="mx-auto h-24 w-24 text-muted-foreground" />
                <h1 className="text-3xl font-bold text-foreground">
                    Your cart is empty
                </h1>
                <p className="text-muted-foreground">
                    Add some products to get started!
                </p>
                <Link href="/shop">
                    <Button className="bg-primary hover:bg-primary/90">
                        Continue Shopping
                    </Button>
                </Link>
            </div>
        </div>
    );
}

export default function Index({
    cart_items,
    order_summary,
}: ShoppingCartProps) {
    const { filters = { search: '' } } = usePage().props as {
        filters?: {
            search: string;
        };
    };
    const [items, setItems] = useState(cart_items.data);
    const [pendingUpdate, setPendingUpdate] = useState<{
        id: number;
        qty: number;
    } | null>(null);
    const [loading, setLoading] = useState(false);
    const [term, setTerm] = useState(filters.search ?? '');
    const [debouncedPending] = useDebounce(pendingUpdate, 500);
    const [debounceSearch] = useDebounce(term, 500);
    useEffect(() => {
        setItems(cart_items.data);
    }, [cart_items]);
    useEffect(() => {
        console.log('trigger2');
        const hasLocalChanges = term !== (filters.search ?? '');
        if (!hasLocalChanges) return;
        const options = {
            query: {
                ...(term && { search: term }),
            },
        };
        const url = cartIndex.url(options);
        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, [debounceSearch]);
    useEffect(() => {
        if (!debouncedPending) return;
        console.log('trigger3');

        router.patch(
            update(debouncedPending.id),
            {
                quantity: debouncedPending.qty,
            },
            {
                preserveScroll: true,
                onStart: () => setLoading(true),
                onFinish: () => setLoading(false),
            },
        );
    }, [debouncedPending]);

    const updateLocalAndTrigger = (id: number, quantity: number) => {
        setItems((prev) =>
            prev.map((item) =>
                Number(item.id) === id ? { ...item, quantity } : item,
            ),
        );
        setPendingUpdate({ id, qty: quantity });
    };
    const decrementQuantity = (id: number) => {
        const item = items.find((i) => Number(i.id) === id);
        if (!item || item.quantity <= 1) return;
        updateLocalAndTrigger(id, item.quantity - 1);
    };
    const incrementQuantity = (id: number) => {
        const item = items.find((i) => Number(i.id) === id);
        if (!item || item.quantity >= item.product.stock) return;
        updateLocalAndTrigger(id, item.quantity + 1);
    };
    const removeItem = (id: number) => {
        router.delete(destroy(id), {
            onSuccess: () => toast.success('Cart item deleted successfully!'),
            onError: () => toast.error('Cart item deletion failed'),
            onStart: () => setLoading(true),
            onFinish: () => setLoading(false),
            preserveScroll: true,
        });
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Shop" />
            <Container className="bg-background px-4 py-8">
                <h1 className="mb-8 text-4xl font-bold text-foreground">
                    Shopping Cart
                </h1>
                <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div className="space-y-4 lg:col-span-2">
                        {/* Search Box */}
                        {items && items?.length > 0 && (
                            <div className="mb-6">
                                <div className="relative">
                                    <Search className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
                                    <Input
                                        type="search"
                                        value={term}
                                        onChange={(e) =>
                                            setTerm(e.target.value)
                                        }
                                        placeholder="Search cart items by name or category..."
                                        className="h-12 border-border bg-card pl-11 text-base shadow-card"
                                    />
                                </div>
                            </div>
                        )}
                        {!items || items?.length === 0
                            ? emptyCart()
                            : items.map(({ id, quantity, product }) => (
                                  <Card key={id} className="shadow-card">
                                      <CardContent className="p-4">
                                          <div className="flex gap-4">
                                              <Link href={`#`}>
                                                  <img
                                                      src={product.image_url}
                                                      alt={product.name}
                                                      className="h-24 w-24 rounded-lg object-cover"
                                                  />
                                              </Link>

                                              <div className="flex-1 space-y-2">
                                                  <div className="flex items-start justify-between">
                                                      <div>
                                                          <Link href={`#`}>
                                                              <h3 className="font-semibold text-foreground transition-colors hover:text-primary">
                                                                  {product.name}
                                                              </h3>
                                                          </Link>
                                                          <p className="text-sm text-muted-foreground">
                                                              {
                                                                  product
                                                                      .category
                                                                      .name
                                                              }
                                                          </p>
                                                      </div>
                                                      <Button
                                                          variant="ghost"
                                                          size="icon"
                                                          onClick={() =>
                                                              removeItem(
                                                                  Number(id),
                                                              )
                                                          }
                                                          disabled={loading}
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
                                                              className="h-8 w-8 cursor-pointer"
                                                              disabled={loading}
                                                              onClick={() =>
                                                                  decrementQuantity(
                                                                      Number(
                                                                          id,
                                                                      ),
                                                                  )
                                                              }
                                                          >
                                                              <Minus className="h-4 w-4" />
                                                          </Button>
                                                          <span className="w-10 text-center font-semibold">
                                                              {quantity}
                                                          </span>
                                                          <Button
                                                              variant="ghost"
                                                              size="icon"
                                                              className="h-8 w-8 cursor-pointer"
                                                              disabled={loading}
                                                              onClick={() =>
                                                                  incrementQuantity(
                                                                      Number(
                                                                          id,
                                                                      ),
                                                                  )
                                                              }
                                                          >
                                                              <Plus className="h-4 w-4" />
                                                          </Button>
                                                      </div>
                                                      <p className="text-xl font-bold text-foreground">
                                                          $
                                                          {product.price *
                                                              quantity}
                                                      </p>
                                                  </div>
                                              </div>
                                          </div>
                                      </CardContent>
                                  </Card>
                              ))}
                        <Pagination links={cart_items?.meta?.links} />
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
                                            ${order_summary.sub_total}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-foreground">
                                        <span>Shipping</span>
                                        <span className="font-semibold">
                                            {order_summary.shipping_fee === 0
                                                ? 'FREE'
                                                : `$${order_summary.shipping_fee}`}
                                        </span>
                                    </div>
                                    {order_summary.shipping_fee === 0 && (
                                        <p className="text-sm text-primary">
                                            🎉 You qualify for free shipping!
                                        </p>
                                    )}
                                </div>

                                <Separator />

                                <div className="flex justify-between text-xl font-bold text-foreground">
                                    <span>Total</span>
                                    <span>
                                        $
                                        {order_summary.sub_total +
                                            order_summary.shipping_fee}
                                    </span>
                                </div>

                                <Link href={index()}>
                                    <Button className="mb-4 h-12 w-full cursor-pointer bg-primary text-base text-primary-foreground hover:bg-primary/90">
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
