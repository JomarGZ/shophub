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

export default function Home() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Home" />
            <section className="bg-gradient-hero relative w-full py-20 md:py-32">
                <div className="container mx-auto px-4">
                    <div className="mx-auto max-w-3xl text-center text-white">
                        <h1 className="mb-6 text-5xl font-bold md:text-6xl">
                            Discover Amazing Products
                        </h1>
                        <p className="mb-8 text-xl opacity-90 md:text-2xl">
                            Shop the latest trends with unbeatable prices and
                            fast shipping
                        </p>
                        <Link href="/shop">
                            <Button
                                size="lg"
                                className="h-14 bg-card px-8 text-lg text-foreground hover:bg-card/90"
                            >
                                Shop Now <ArrowRight className="ml-2 h-5 w-5" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </section>
            <div className="mx-auto flex h-full max-w-7xl flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                adasdsadsd
            </div>
        </AppLayout>
    );
}
