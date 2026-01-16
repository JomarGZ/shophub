import { Star } from 'lucide-react';
import { Badge } from './ui/badge';
export function RatingStars({ rating }: { rating: number }) {
    return (
        <Badge variant="outline" className="gap-1 border-0">
            {Array.from({
                length: 5,
            }).map((_, index) => (
                <Star
                    key={index}
                    className={`h-3 w-3 ${index < rating ? 'fill-yellow-400 text-yellow-400' : 'text-muted-foreground'}`}
                />
            ))}
        </Badge>
    );
}
