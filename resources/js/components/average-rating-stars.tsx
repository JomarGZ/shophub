import { Star } from 'lucide-react';

export function AverageRatingStars({ rating }: { rating: number }) {
    const maxStars = 5;
    const roundedRating = Math.round(rating * 2) / 2;

    return (
        <div className="flex gap-1">
            {Array.from({ length: maxStars }).map((_, index) => {
                const starClass =
                    index + 1 <= roundedRating
                        ? 'fill-yellow-400 text-yellow-400'
                        : index + 0.5 === roundedRating
                          ? 'fill-yellow-400/50 text-yellow-400/50'
                          : 'text-muted-foreground';

                return <Star key={index} className={`h-5 w-5 ${starClass}`} />;
            })}
        </div>
    );
}
