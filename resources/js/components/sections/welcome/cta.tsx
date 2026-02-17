import { Container } from '@/components/container';
import { Button } from '@/components/ui/button';
import { index } from '@/routes/shop';
import { Link } from '@inertiajs/react';

export function Cta() {
    return (
        <Container
            as="section"
            className="bg-secondary py-20 text-secondary-foreground"
            maxWidth="full"
            innerClassName="mx-auto px-4 text-center"
        >
            <h2 className="mb-4 text-4xl font-bold">
                Ready to Start Shopping?
            </h2>
            <p className="mb-8 text-xl opacity-90">
                Join thousands of happy customers today
            </p>
            <Link href={index()}>
                <Button
                    size="lg"
                    className="h-14 cursor-pointer bg-primary px-8 text-lg text-primary-foreground hover:bg-primary/90"
                >
                    Browse Products
                </Button>
            </Link>
        </Container>
    );
}
