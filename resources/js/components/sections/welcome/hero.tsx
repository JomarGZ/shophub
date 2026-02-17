import { Container } from '@/components/container';
import { Button } from '@/components/ui/button';
import { index } from '@/routes/shop';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

export function Hero() {
    return (
        <Container
            as="section"
            className="bg-gradient-hero py-20 md:py-32"
            maxWidth="md"
        >
            <div className="text-center text-white">
                <h1 className="mb-6 text-5xl font-bold md:text-6xl">
                    Discover Amazing Products
                </h1>
                <p className="mb-8 text-xl opacity-90 md:text-2xl">
                    Shop the latest trends with unbeatable prices and fast
                    shipping
                </p>
                <Link href={index()}>
                    <Button
                        size="lg"
                        className="h-14 cursor-pointer bg-card px-8 text-lg text-foreground hover:bg-card/90"
                    >
                        Shop Now <ArrowRight className="ml-2 h-5 w-5" />
                    </Button>
                </Link>
            </div>
        </Container>
    );
}
