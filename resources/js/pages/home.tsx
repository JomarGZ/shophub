import { index } from '@/actions/App/Http/Controllers/ShopController';
import { Container } from '@/components/container';
import { Features } from '@/components/features';
import { FeaturedProducts } from '@/components/products/featured-products';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '#',
    },
];
interface HomeProps {
    featured_products: any[];
}
export default function Home({ featured_products }: HomeProps) {
    console.log(featured_products);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Home" />
            {/* Hero Section */}
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
            <Container as="section" className="border-b py-16">
                <Features />
            </Container>
            {/* Featured Products Section */}
            <Container as="section" className="py-16">
                <FeaturedProducts featuredProducts={featured_products} />
            </Container>
            {/* CTA Section */}
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
        </AppLayout>
    );
}
