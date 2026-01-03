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
import { useRatingModal } from '@/contexts/rating-modal-context';
import { getPaymentVariant } from '@/lib/utils';
import { Order } from '@/types';
import {
    CheckCircle,
    ChevronDown,
    ChevronUp,
    Star,
    XCircle,
} from 'lucide-react';

type OrderStatusOption = {
    value: string;
    label: string;
    color: string;
};

type OrderCardProps = {
    order: Order;
    statusMap: Record<string, OrderStatusOption>;
    expanded: boolean;
    loading: boolean;
    onToggle: () => void;
    onMarkReceived: (id: number) => void;
    onCancel: (id: number) => void;
};

export function OrderCard({
    order,
    statusMap,
    expanded,
    loading,
    onToggle,
    onMarkReceived,
    onCancel,
}: OrderCardProps) {
    const formatOrderId = (id: string | number) => `ORD-${id}`;
    const { open } = useRatingModal();
    const formatDate = (dateString: string) =>
        new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });

    const handleOpenRatingModal = (
        productSlug: string,
        productName: string,
    ) => {};
    return (
        <Card className="overflow-hidden">
            <CardHeader className="bg-muted/30">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div className="space-y-1">
                        <CardTitle className="text-xl">
                            {formatOrderId(order.id)}
                        </CardTitle>
                        <p className="text-sm text-muted-foreground">
                            Ordered on {formatDate(order.date_ordered)}
                        </p>
                    </div>

                    <div className="flex items-center gap-4">
                        <div className="text-right">
                            <p className="text-sm text-muted-foreground">
                                Total
                            </p>
                            <p className="text-xl font-bold">${order.total}</p>
                        </div>
                        <Badge variant={order.status.color}>
                            {order.status.label}
                        </Badge>
                    </div>
                </div>
            </CardHeader>

            <CardContent className="p-6">
                <div className="flex items-center justify-between">
                    <p className="text-sm text-muted-foreground">
                        {order.order_items?.length} item
                        {order.order_items?.length !== 1 ? 's' : ''}
                    </p>

                    <div className="flex flex-wrap items-center gap-2">
                        {order.status.value ===
                            statusMap['out_for_delivery']?.value && (
                            <Button
                                disabled={loading}
                                size="sm"
                                onClick={() => onMarkReceived(order.id)}
                                className="gap-2"
                            >
                                <CheckCircle className="h-4 w-4" />
                                Mark as Received
                            </Button>
                        )}

                        {order.status.value === statusMap['pending']?.value && (
                            <Button
                                disabled={loading}
                                variant="destructive"
                                size="sm"
                                onClick={() => onCancel(order.id)}
                                className="gap-2"
                            >
                                <XCircle className="h-4 w-4" />
                                Cancel Order
                            </Button>
                        )}

                        <Button
                            variant="ghost"
                            size="sm"
                            onClick={onToggle}
                            className="cursor-pointer gap-2"
                        >
                            {expanded ? (
                                <>
                                    Hide Details
                                    <ChevronUp className="h-4 w-4" />
                                </>
                            ) : (
                                <>
                                    View Details
                                    <ChevronDown className="h-4 w-4" />
                                </>
                            )}
                        </Button>
                    </div>
                </div>

                {expanded && (
                    <div className="mt-6 space-y-6 border-t pt-6">
                        {/* Payment & Shipping */}
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div className="space-y-3">
                                <h4 className="text-sm font-semibold text-muted-foreground">
                                    Payment Information
                                </h4>
                                <div className="space-y-2">
                                    <div className="flex justify-between">
                                        <span className="text-sm">
                                            Payment Status:
                                        </span>
                                        <Badge
                                            variant={getPaymentVariant(
                                                order.payment_status ??
                                                    'unknown',
                                            )}
                                        >
                                            {order.payment_status ?? 'Unknown'}
                                        </Badge>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-sm">
                                            Payment Method:
                                        </span>
                                        <span className="text-sm font-medium">
                                            {order.payment_method}
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
                                            Shipping Fee:
                                        </span>
                                        <span className="text-sm font-medium">
                                            ${order.shipping_fee}
                                        </span>
                                    </div>
                                    <div>
                                        <p className="mb-1 text-sm font-medium">
                                            Delivery Address:
                                        </p>
                                        <p className="text-sm text-muted-foreground">
                                            {order.address.country}
                                            <br />
                                            {order.address.city},{' '}
                                            {order.address.street}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Order Items */}
                        <div>
                            <h4 className="mb-4 font-semibold">Order Items</h4>
                            <div className="rounded-md border">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Product</TableHead>
                                            <TableHead className="text-right">
                                                Quantity
                                            </TableHead>
                                            <TableHead className="text-right">
                                                Price
                                            </TableHead>
                                            <TableHead className="text-right">
                                                Total
                                            </TableHead>
                                            {order.status.value ===
                                                'delivered' && (
                                                <TableHead className="text-center">
                                                    Rating
                                                </TableHead>
                                            )}
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {(order.order_items ?? []).map(
                                            (item) => (
                                                <TableRow key={item.id}>
                                                    <TableCell className="font-medium">
                                                        {item.product_name}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        {item.quantity}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        ${item.product_price}
                                                    </TableCell>
                                                    <TableCell className="text-right font-medium">
                                                        $
                                                        {item.quantity *
                                                            Number(
                                                                item.product_price,
                                                            )}
                                                    </TableCell>
                                                    {order.status.value ===
                                                        'delivered' && (
                                                        <TableCell className="text-center">
                                                            {item.has_rated ? (
                                                                <Badge
                                                                    variant="secondary"
                                                                    className="gap-1"
                                                                >
                                                                    <Star className="h-3 w-3 fill-yellow-400 text-yellow-400" />
                                                                    Rated
                                                                </Badge>
                                                            ) : (
                                                                <Button
                                                                    variant="ghost"
                                                                    size="sm"
                                                                    onClick={() =>
                                                                        open({
                                                                            productName:
                                                                                item
                                                                                    .product
                                                                                    ?.name ??
                                                                                '',
                                                                            productSlug:
                                                                                item
                                                                                    .product
                                                                                    ?.slug ??
                                                                                '',
                                                                        })
                                                                    }
                                                                    className="cursor-pointer gap-1 text-xs"
                                                                >
                                                                    <Star className="h-3 w-3" />
                                                                    Rate
                                                                </Button>
                                                            )}
                                                        </TableCell>
                                                    )}
                                                </TableRow>
                                            ),
                                        )}
                                        <TableRow>
                                            <TableCell
                                                colSpan={
                                                    order.status.value ===
                                                    'delivered'
                                                        ? 4
                                                        : 3
                                                }
                                                className="text-right text-sm"
                                            >
                                                Subtotal
                                            </TableCell>
                                            <TableCell className="text-right font-medium">
                                                {order.total}
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell
                                                colSpan={
                                                    order.status.value ===
                                                    'delivered'
                                                        ? 4
                                                        : 3
                                                }
                                                className="text-right text-sm"
                                            >
                                                Shipping Fee
                                            </TableCell>
                                            <TableCell className="text-right font-medium">
                                                ${order.shipping_fee}
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell
                                                colSpan={
                                                    order.status.value ===
                                                    'delivered'
                                                        ? 4
                                                        : 3
                                                }
                                                className="text-right font-semibold"
                                            >
                                                Order Total
                                            </TableCell>
                                            <TableCell className="text-right text-lg font-bold">
                                                ${order.total}
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
    );
}
