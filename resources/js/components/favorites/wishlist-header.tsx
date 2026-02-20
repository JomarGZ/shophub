import { Heart } from 'lucide-react';

type Props = {
    total: number | undefined;
};
export function WishlistHeader({ total }: Props) {
    return (
        <div className="animate-fade-in mb-8">
            <div className="mb-2 flex items-center gap-3">
                <div className="bg-gradient-hero flex h-12 w-12 items-center justify-center rounded-full">
                    <Heart className="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 className="text-3xl font-bold text-foreground">
                        My Favorites
                    </h1>
                    {total && (
                        <p className="text-muted-foreground">
                            {total} {total === 1 ? 'item' : 'items'} Saved
                        </p>
                    )}
                </div>
            </div>
        </div>
    );
}
