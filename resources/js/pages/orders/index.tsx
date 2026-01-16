import OrderController from '@/actions/App/Http/Controllers/OrderController';
import { Container } from '@/components/container';
import { OrderCard } from '@/components/orders/order-card';
import { RatingModal } from '@/components/orders/rating-modal';
import { ReceivedInfoModal } from '@/components/orders/received-info-modal';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    RatingModalProvider,
    useRatingModal,
} from '@/contexts/rating-modal-context';
import AppLayout from '@/layouts/app-layout';
import { Order, PaginatedResponse } from '@/types';
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
    orders: PaginatedResponse<Order>;
    order_statuses: OrderStatusOption[];
};
export default function Index() {
    const { orders, order_statuses } = usePage<IndexProps>().props;
    const [receiveModalOpen, setReceiveModalOpen] = useState<boolean>(false);

    const statusMap: Record<string, OrderStatusOption> = Object.fromEntries(
        order_statuses.map((s) => [s.value, s]),
    );
    const [loading, setLoading] = useState<boolean>(false);

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
                onSuccess: () => {
                    toast.success('Cancel Order Successfully');
                },
                onError: (props) =>
                    toast.error(props.status || 'Cancel Order Failed'),
                onStart: () => setLoading(true),
                onFinish: () => setLoading(false),
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
                        {orders.data.length === 0 ? (
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
                                {orders.data.map((order) => (
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
                            </>
                        )}
                    </div>
                    <Pagination links={orders.meta.links} />
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
    return (
        <RatingModal
            isOpen={isOpen}
            onClose={close}
            productSlug={itemToRate?.productSlug ?? ''}
            productName={itemToRate?.productName ?? null}
        />
    );
}
