import React from 'react';
import { Card, CardContent } from '../ui/card';

type FeatureProps = {
    icon: React.ReactNode;
    title: string;
    description: string;
};
export function WishlistFeatureCard({
    icon,
    title,
    description,
}: FeatureProps) {
    return (
        <Card className="border-border">
            <CardContent className="flex items-start gap-3 p-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                    {icon}
                </div>
                <div>
                    <h3 className="mb-1 font-semibold text-foreground">
                        {title}
                    </h3>
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </CardContent>
        </Card>
    );
}
