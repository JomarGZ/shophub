import { Container } from '@/components/container';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { getOrderVariant, getPaymentVariant } from '@/lib/utils';
import { Head } from '@inertiajs/react';
import { ChevronDown, ChevronUp, Package } from 'lucide-react';
import { useState } from 'react';

export default function Index({ orders }: { orders: any[] }) {
    const [expandedOrders, setExpandedOrders] = useState<Set<string>>(
        new Set(),
    );
    const [visibleCount, setVisibleCount] = useState(5);

    const hasMore = visibleCount < orders.length;

    const loadMore = () => {
        setVisibleCount((prev) => Math.min(prev + 5, orders.length));
    };

    const toggleOrderDetails = (orderId: string) => {
        const newExpanded = new Set(expandedOrders);
        if (newExpanded.has(orderId)) {
            newExpanded.delete(orderId);
        } else {
            newExpanded.add(orderId);
        }
        setExpandedOrders(newExpanded);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
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
                                <Card
                                    key={order.id}
                                    className="overflow-hidden"
                                >
                                    <CardHeader className="bg-muted/30">
                                        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div className="space-y-1">
                                                <CardTitle className="text-xl">
                                                    {order.id}
                                                </CardTitle>
                                                <p className="text-sm text-muted-foreground">
                                                    Ordered on{' '}
                                                    {formatDate(
                                                        order.date_ordered,
                                                    )}
                                                </p>
                                            </div>
                                            <div className="flex items-center gap-4">
                                                <div className="text-right">
                                                    <p className="text-sm text-muted-foreground">
                                                        Total
                                                    </p>
                                                    <p className="text-xl font-bold">
                                                        ${order.total}
                                                    </p>
                                                </div>
                                                <Badge
                                                    variant={getOrderVariant(
                                                        order.status.value,
                                                    )}
                                                >
                                                    {order.status.label}
                                                </Badge>
                                            </div>
                                        </div>
                                    </CardHeader>

                                    <CardContent className="p-6">
                                        <div className="flex items-center justify-between">
                                            <p className="text-sm text-muted-foreground">
                                                {order.order_items.length} item
                                                {order.order_items.length !== 1
                                                    ? 's'
                                                    : ''}
                                            </p>
                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                onClick={() =>
                                                    toggleOrderDetails(order.id)
                                                }
                                                className="gap-2"
                                            >
                                                {expandedOrders.has(
                                                    order.id,
                                                ) ? (
                                                    <>
                                                        Hide Details{' '}
                                                        <ChevronUp className="h-4 w-4" />
                                                    </>
                                                ) : (
                                                    <>
                                                        View Details{' '}
                                                        <ChevronDown className="h-4 w-4" />
                                                    </>
                                                )}
                                            </Button>
                                        </div>

                                        {/* Order Items Details */}
                                        {expandedOrders.has(order.id) && (
                                            <div className="mt-6 space-y-6 border-t pt-6">
                                                {/* Payment & Shipping Information */}
                                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                                    <div className="space-y-3">
                                                        <h4 className="text-sm font-semibold text-muted-foreground">
                                                            Payment Information
                                                        </h4>
                                                        <div className="space-y-2">
                                                            <div className="flex items-center justify-between">
                                                                <span className="text-sm">
                                                                    Payment
                                                                    Status:
                                                                </span>
                                                                <Badge
                                                                    variant={getPaymentVariant(
                                                                        order
                                                                            .payment
                                                                            .status,
                                                                    )}
                                                                >
                                                                    {
                                                                        order
                                                                            .payment
                                                                            .status
                                                                    }
                                                                </Badge>
                                                            </div>
                                                            <div className="flex justify-between">
                                                                <span className="text-sm">
                                                                    Payment
                                                                    Method:
                                                                </span>
                                                                <span className="text-sm font-medium">
                                                                    {
                                                                        order.paymentMethod
                                                                    }
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="space-y-3">
                                                        <h4 className="text-sm font-semibold text-muted-foreground">
                                                            Shipping Information
                                                        </h4>
                                                        <div className="space-y-2">
                                                            <div className="flex justify-between">
                                                                <span className="text-sm">
                                                                    Shipping
                                                                    Fee:
                                                                </span>
                                                                <span className="text-sm font-medium">
                                                                    $
                                                                    {
                                                                        order.shipping_fee
                                                                    }
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <p className="mb-1 text-sm font-medium">
                                                                    Delivery
                                                                    Address:
                                                                </p>
                                                                <p className="text-sm text-muted-foreground">
                                                                    {
                                                                        order
                                                                            .address
                                                                            .street
                                                                    }
                                                                    <br />
                                                                    {
                                                                        order
                                                                            .address
                                                                            .city
                                                                    }
                                                                    ,{' '}
                                                                    {
                                                                        order
                                                                            .address
                                                                            .zipCode
                                                                    }
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Order Items */}
                                                <div>
                                                    <h4 className="mb-4 font-semibold">
                                                        Order Items
                                                    </h4>
                                                    <div className="rounded-md border">
                                                        <Table>
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead>
                                                                        Product
                                                                    </TableHead>
                                                                    <TableHead className="text-right">
                                                                        Quantity
                                                                    </TableHead>
                                                                    <TableHead className="text-right">
                                                                        Price
                                                                    </TableHead>
                                                                    <TableHead className="text-right">
                                                                        Total
                                                                    </TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody>
                                                                {order.order_items.map(
                                                                    (item) => (
                                                                        <TableRow
                                                                            key={
                                                                                item.id
                                                                            }
                                                                        >
                                                                            <TableCell className="font-medium">
                                                                                {
                                                                                    item.product_name
                                                                                }
                                                                            </TableCell>
                                                                            <TableCell className="text-right">
                                                                                {
                                                                                    item.quantity
                                                                                }
                                                                            </TableCell>
                                                                            <TableCell className="text-right">
                                                                                $
                                                                                {
                                                                                    item.product_price
                                                                                }
                                                                            </TableCell>
                                                                            <TableCell className="text-right font-medium">
                                                                                $
                                                                                {item.quantity *
                                                                                    item.product_price}
                                                                            </TableCell>
                                                                        </TableRow>
                                                                    ),
                                                                )}
                                                                <TableRow>
                                                                    <TableCell
                                                                        colSpan={
                                                                            3
                                                                        }
                                                                        className="text-right text-sm"
                                                                    >
                                                                        Subtotal
                                                                    </TableCell>
                                                                    <TableCell className="text-right font-medium">
                                                                        $
                                                                        {(
                                                                            order.total -
                                                                            order.shipping_fee
                                                                        ).toFixed(
                                                                            2,
                                                                        )}
                                                                    </TableCell>
                                                                </TableRow>
                                                                <TableRow>
                                                                    <TableCell
                                                                        colSpan={
                                                                            3
                                                                        }
                                                                        className="text-right text-sm"
                                                                    >
                                                                        Shipping
                                                                        Fee
                                                                    </TableCell>
                                                                    <TableCell className="text-right font-medium">
                                                                        $
                                                                        {
                                                                            order.shipping_fee
                                                                        }
                                                                    </TableCell>
                                                                </TableRow>
                                                                <TableRow>
                                                                    <TableCell
                                                                        colSpan={
                                                                            3
                                                                        }
                                                                        className="text-right font-semibold"
                                                                    >
                                                                        Order
                                                                        Total
                                                                    </TableCell>
                                                                    <TableCell className="text-right text-lg font-bold">
                                                                        $
                                                                        {
                                                                            order.total
                                                                        }
                                                                    </TableCell>
                                                                </TableRow>
                                                            </TableBody>
                                                        </Table>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </CardContent>
                                </Card>
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
        </AppLayout>
    );
}
