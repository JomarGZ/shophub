import { cn } from '@/lib/utils';
import { Star } from 'lucide-react';

interface AverageRatingStarsProps {
    rating: number;
    size?: 'sm' | 'md' | 'lg';
}
export function AverageRatingStars({
    rating,
    size = 'md',
}: AverageRatingStarsProps) {
    const maxStars = 5;
    const roundedRating = Math.round(rating * 2) / 2;
    const sizeClasses = {
        sm: 'h-3.5 w-3.5',
        md: 'h-5 w-5',
        lg: 'h-6 w-6',
    };

    return (
        <div className="flex gap-1">
            {Array.from({ length: maxStars }).map((_, index) => {
                const isFull = index + 1 <= roundedRating;
                const isHalf = index + 0.5 === roundedRating;

                return (
                    <Star
                        key={index}
                        className={cn(
                            sizeClasses[size],
                            isFull && 'fill-yellow-400 text-yellow-400',
                            isHalf && 'fill-yellow-400/50 text-yellow-400/50',
                            !isFull && !isHalf && 'text-muted-foreground',
                        )}
                    />
                );
            })}
        </div>
    );
}
