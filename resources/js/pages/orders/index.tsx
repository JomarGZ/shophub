import OrderController from '@/actions/App/Http/Controllers/OrderController';
import { Container } from '@/components/container';
import { OrderCard } from '@/components/orders/order-card';
import { RatingModal } from '@/components/orders/rating-modal';
import { ReceivedInfoModal } from '@/components/orders/received-info-modal';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    RatingModalProvider,
    useRatingModal,
} from '@/contexts/rating-modal-context';
import AppLayout from '@/layouts/app-layout';
import { Order, SimplePaginatedResponse } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { Package } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

type OrderStatusOption = {
    value: string;
    label: string;
    color: string;
};
type IndexProps = {
    orders: SimplePaginatedResponse<Order>;
    order_statuses: OrderStatusOption[];
};
export default function Index() {
    const { orders, order_statuses } = usePage<IndexProps>().props;
    console.log(orders);
    const [receiveModalOpen, setReceiveModalOpen] = useState<boolean>(false);
    const [orderList, setOrderList] = useState<Order[]>(orders.data);
    const [nextPageUrl, setNextPageUrl] = useState<string | null>(
        orders.next_page_url ? String(orders.next_page_url) : null,
    );
    const statusMap: Record<string, OrderStatusOption> = Object.fromEntries(
        order_statuses.map((s) => [s.value, s]),
    );
    const [loading, setLoading] = useState<boolean>(false);

    const [hasMore, setHasMore] = useState<Boolean>(orders.has_more);
    const [expandedOrders, setExpandedOrders] = useState<Set<string>>(
        new Set(),
    );
    const toggleOrderDetails = (orderId: string) => {
        const newExpanded = new Set(expandedOrders);
        if (newExpanded.has(orderId)) {
            newExpanded.delete(orderId);
        } else {
            newExpanded.add(orderId);
        }
        setExpandedOrders(newExpanded);
    };

    const handleMarkReceived = (id: number) => {
        if (loading) return;
        router.patch(
            OrderController.update(id),
            {
                status: statusMap['delivered'].value,
            },
            {
                preserveScroll: true,
                onSuccess: ({ props: { flash } }: any) => {
                    setReceiveModalOpen(true);
                    setOrderList((prev) =>
                        prev.map((o) =>
                            o.id === id
                                ? {
                                      ...o,
                                      status: statusMap[
                                          'delivered'
                                      ] as unknown as Order['status'],
                                  }
                                : o,
                        ),
                    );
                },
                onError: ({ props: { flash } }: any) =>
                    toast.error(flash.error || 'Failed to mark as Recieved'),
                onStart: () => setLoading(true),
                onFinish: () => setLoading(false),
            },
        );
    };
    const handleCancelOrder = (id: number) => {
        if (loading) return;
        router.patch(
            OrderController.update(id),
            {
                status: statusMap['cancelled'].value,
            },
            {
                preserveScroll: true,
                onSuccess: ({ props: { flash } }: any) => {
                    toast.success(flash.success || 'Cancel Order Successfully');
                    setOrderList((prev) =>
                        prev.map((o) =>
                            o.id === id
                                ? {
                                      ...o,
                                      status: statusMap[
                                          'cancelled'
                                      ] as unknown as Order['status'],
                                  }
                                : o,
                        ),
                    );
                },
                onError: ({ props: { flash } }: any) =>
                    toast.error(flash.error || 'Cancel Order Failed'),
                onStart: () => setLoading(true),
                onFinish: () => setLoading(false),
            },
        );
    };

    const loadMore = () => {
        if (!hasMore || !nextPageUrl) return;

        router.get(
            nextPageUrl,
            {},
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (page) => {
                    const orders = page.props
                        .orders as SimplePaginatedResponse<Order>;
                    setOrderList((prev) => [...prev, ...orders.data]);
                    setNextPageUrl(
                        orders.next_page_url
                            ? String(orders.next_page_url)
                            : null,
                    );
                    setHasMore(orders.has_more);
                },
            },
        );
    };
    return (
        <AppLayout>
            <Head title="Orders" />
            <Container>
                <h1 className="mb-2 text-4xl font-bold text-foreground">
                    My Orders
                </h1>
                <p className="text-muted-foreground">
                    View and track all your orders in one place
                </p>
            </Container>
            <RatingModalProvider>
                <Container>
                    <div className="space-y-4">
                        {orderList.length === 0 ? (
                            <Card>
                                <CardContent className="py-16 text-center">
                                    <Package className="mx-auto mb-4 h-16 w-16 text-muted-foreground" />
                                    <h3 className="mb-2 text-xl font-semibold">
                                        No orders yet
                                    </h3>
                                    <p className="mb-6 text-muted-foreground">
                                        Start shopping to see your orders here
                                    </p>
                                    <Button asChild>
                                        <a href="/shop">Browse Products</a>
                                    </Button>
                                </CardContent>
                            </Card>
                        ) : (
                            <>
                                {orderList.map((order) => (
                                    <OrderCard
                                        key={order.id}
                                        order={order}
                                        statusMap={statusMap}
                                        expanded={expandedOrders.has(
                                            String(order.id),
                                        )}
                                        loading={loading}
                                        onToggle={() =>
                                            toggleOrderDetails(String(order.id))
                                        }
                                        onMarkReceived={handleMarkReceived}
                                        onCancel={handleCancelOrder}
                                    />
                                ))}

                                {/* Load More Button */}
                                {hasMore && (
                                    <div className="flex justify-center pt-6">
                                        <Button
                                            variant="outline"
                                            size="lg"
                                            onClick={loadMore}
                                            className="min-w-[200px]"
                                        >
                                            Load More Orders
                                        </Button>
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                </Container>
                <ReceivedInfoModal
                    isOpen={receiveModalOpen}
                    onClose={() => setReceiveModalOpen(false)}
                />
                <RatingModalWrapper />
            </RatingModalProvider>
        </AppLayout>
    );
}

function RatingModalWrapper() {
    const { isOpen, close, itemToRate } = useRatingModal();

    const handleSubmit = () => {
        // your submit logic here
        close();
    };

    return (
        <RatingModal
            isOpen={isOpen}
            onClose={close}
            onSubmit={handleSubmit}
            productSlug={itemToRate?.productSlug ?? ''}
            productName={itemToRate?.productName ?? null}
        />
    );
}
