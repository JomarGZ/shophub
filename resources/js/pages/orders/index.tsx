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
import { Head } from '@inertiajs/react';
import { ChevronDown, ChevronUp, Package } from 'lucide-react';
import { useState } from 'react';

export default function Index({ orders }: { orders: any[] }) {
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

    const getStatusVariant = (status: string) => {
        switch (status) {
            case 'delivered':
                return 'default';
            case 'shipped':
                return 'secondary';
            case 'pending':
                return 'outline';
            case 'cancelled':
                return 'destructive';
            default:
                return 'outline';
        }
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
                    {orders.length === 0 ? (
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
                        orders.map((order) => (
                            <Card key={order.id} className="overflow-hidden">
                                <CardHeader className="bg-muted/30">
                                    <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div className="space-y-1">
                                            <CardTitle className="text-xl">
                                                {order.id}
                                            </CardTitle>
                                            <p className="text-sm text-muted-foreground">
                                                Ordered on{' '}
                                                {formatDate(order.date)}
                                            </p>
                                        </div>
                                        <div className="flex items-center gap-4">
                                            <div className="text-right">
                                                <p className="text-sm text-muted-foreground">
                                                    Total
                                                </p>
                                                <p className="text-xl font-bold">
                                                    ${order.total.toFixed(2)}
                                                </p>
                                            </div>
                                            <Badge
                                                variant={getStatusVariant(
                                                    order.status,
                                                )}
                                            >
                                                {order.status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                    order.status.slice(1)}
                                            </Badge>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent className="p-6">
                                    <div className="flex items-center justify-between">
                                        <p className="text-sm text-muted-foreground">
                                            {order.items.length} item
                                            {order.items.length !== 1
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
                                            {expandedOrders.has(order.id) ? (
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
                                        <div className="mt-6 border-t pt-6">
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
                                                        {order.items.map(
                                                            (item, index) => (
                                                                <TableRow
                                                                    key={index}
                                                                >
                                                                    <TableCell className="font-medium">
                                                                        {
                                                                            item.productName
                                                                        }
                                                                    </TableCell>
                                                                    <TableCell className="text-right">
                                                                        {
                                                                            item.quantity
                                                                        }
                                                                    </TableCell>
                                                                    <TableCell className="text-right">
                                                                        $
                                                                        {item.price.toFixed(
                                                                            2,
                                                                        )}
                                                                    </TableCell>
                                                                    <TableCell className="text-right font-medium">
                                                                        $
                                                                        {(
                                                                            item.quantity *
                                                                            item.price
                                                                        ).toFixed(
                                                                            2,
                                                                        )}
                                                                    </TableCell>
                                                                </TableRow>
                                                            ),
                                                        )}
                                                        <TableRow>
                                                            <TableCell
                                                                colSpan={3}
                                                                className="text-right font-semibold"
                                                            >
                                                                Order Total
                                                            </TableCell>
                                                            <TableCell className="text-right text-lg font-bold">
                                                                $
                                                                {order.total.toFixed(
                                                                    2,
                                                                )}
                                                            </TableCell>
                                                        </TableRow>
                                                    </TableBody>
                                                </Table>
                                            </div>
                                        </div>
                                    )}
                                </CardContent>
                            </Card>
                        ))
                    )}
                </div>
            </Container>
        </AppLayout>
    );
}
