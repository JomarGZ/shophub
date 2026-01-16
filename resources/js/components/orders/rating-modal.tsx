import ProductRatingController from '@/actions/App/Http/Controllers/ProductRatingController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { router, useForm } from '@inertiajs/react';
import { Star } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

interface RatingModalProps {
    isOpen: boolean;
    onClose: () => void;
    productSlug: string;
    productName: string | null;
}

export const RatingModal = ({
    isOpen,
    onClose,
    productSlug,
    productName,
}: RatingModalProps) => {
    const [hoveredRating, setHoveredRating] = useState(0);

    const form = useForm({
        rating: 0,
        comment: '',
    });
    const handleSubmit = () => {
        form.post(ProductRatingController.store(productSlug).url, {
            onSuccess: () => {
                toast.success('Thank you for your rating!');
                router.reload({
                    only: ['orders'],
                });
            },
            onError: () =>
                toast.error('Failed to submit rating. Please try again.'),
            onFinish: () => handleClose(),
            preserveScroll: true,
        });
    };

    const resetState = () => {
        setHoveredRating(0);
    };

    const handleClose = () => {
        resetState();
        onClose();
    };

    const displayRating = hoveredRating || form.data.rating;

    const getRatingLabel = (r: number) => {
        if (r === 1) return 'Poor';
        if (r === 2) return 'Fair';
        if (r === 3) return 'Good';
        if (r === 4) return 'Very Good';
        if (r === 5) return 'Excellent';
        return '';
    };

    return (
        <Dialog open={isOpen} onOpenChange={handleClose}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle className="text-center text-xl">
                        Rate Product
                    </DialogTitle>
                    <DialogDescription className="text-center">
                        How would you rate{' '}
                        <span className="font-medium text-foreground">
                            {productName ?? 'this product'}
                        </span>
                        ?
                    </DialogDescription>
                </DialogHeader>

                <div className="space-y-4 py-6">
                    {/* Star Rating */}
                    <div className="flex flex-col items-center gap-3">
                        <div className="flex gap-1">
                            {[1, 2, 3, 4, 5].map((star) => (
                                <button
                                    key={star}
                                    type="button"
                                    onClick={() => form.setData('rating', star)}
                                    onMouseEnter={() => setHoveredRating(star)}
                                    onMouseLeave={() => setHoveredRating(0)}
                                    className="cursor-pointer rounded p-1 transition-transform hover:scale-110 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
                                >
                                    <Star
                                        className={`h-8 w-8 transition-colors ${
                                            star <= displayRating
                                                ? 'fill-yellow-400 text-yellow-400'
                                                : 'text-muted-foreground/30'
                                        }`}
                                    />
                                </button>
                            ))}
                        </div>
                        {displayRating > 0 && (
                            <span className="text-sm font-medium text-muted-foreground">
                                {getRatingLabel(displayRating)}
                            </span>
                        )}
                    </div>

                    {/* Optional Comment */}
                    <Textarea
                        placeholder="Add a comment (optional)..."
                        value={form.data.comment}
                        onChange={(e) =>
                            form.setData('comment', e.target.value)
                        }
                        rows={3}
                        className="resize-none"
                    />
                </div>

                <DialogFooter className="flex-col gap-2 sm:flex-row">
                    <Button
                        variant="outline"
                        onClick={handleClose}
                        className="w-full sm:w-auto"
                    >
                        Cancel
                    </Button>
                    <Button
                        onClick={handleSubmit}
                        disabled={form.processing || form.data.rating === 0}
                        className="w-full sm:w-auto"
                    >
                        Submit Rating
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};
