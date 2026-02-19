import { FeaturedProducts } from '@/components/products/featured-products';
import { Cta } from '@/components/welcome/cta';
import { Features } from '@/components/welcome/features';
import { Hero } from '@/components/welcome/hero';
import AppLayout from '@/layouts/app-layout';
import { Product, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '#',
    },
];
type PageProps = {
    featured_products: Product[];
};

export default function Welcome() {
    const { featured_products } = usePage<PageProps>().props;
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Welcome to ShopHub" />
            <Hero />
            <Features />
            <FeaturedProducts featuredProducts={featured_products} />
            <Cta />
        </AppLayout>
    );
}
