import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { CheckCircle, Star } from 'lucide-react';

interface ReceivedInfoModalProps {
    isOpen: boolean;
    onClose: () => void;
}

export const ReceivedInfoModal = ({
    isOpen,
    onClose,
}: ReceivedInfoModalProps) => {
    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                        <CheckCircle className="h-6 w-6 text-primary" />
                    </div>
                    <DialogTitle className="text-center text-xl">
                        Order Received!
                    </DialogTitle>
                    <DialogDescription className="text-center">
                        Thank you for confirming your order delivery.
                    </DialogDescription>
                </DialogHeader>

                <div className="py-4">
                    <div className="flex items-start gap-3 rounded-lg border bg-muted/50 p-4">
                        <Star className="mt-0.5 h-5 w-5 flex-shrink-0 text-yellow-500" />
                        <div className="space-y-1">
                            <p className="text-sm font-medium">
                                Quick rate your products!
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Your ratings help us track our best products and
                                improve our service. You can rate each item in
                                your order details.
                            </p>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button onClick={onClose} className="w-full cursor-pointer">
                        Got it!
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};
