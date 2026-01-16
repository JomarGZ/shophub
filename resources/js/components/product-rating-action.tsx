import { Star } from 'lucide-react';
import { RatingStars } from './rating-stars';
import { Button } from './ui/button';

type ProductRatingActionProps = {
    hasRated: boolean;
    rating?: number;
    onRate: () => void;
};
export default function ProductRatingAction({
    hasRated,
    rating = 0,
    onRate,
}: ProductRatingActionProps) {
    if (!hasRated) {
        return (
            <Button
                variant="ghost"
                size="sm"
                onClick={onRate}
                className="cursor-pointer gap-1 text-xs"
            >
                <Star className="h-3 w-3" />
                Rate
            </Button>
        );
    }
    return <RatingStars rating={rating} />;
}
