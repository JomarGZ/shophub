import { PaginationMetaLink } from '@/types';
import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

type PaginationProps = {
    links: PaginationMetaLink[];
};

export function Pagination({ links }: PaginationProps) {
    return (
        <>
            {links.length > 3 && (
                <div className="mt-6 flex items-center justify-center gap-2">
                    {links.map((link, i) => {
                        const label = link.label;
                        const isPageNumber = !isNaN(Number(label));
                        const isPrevious = label.includes('Previous');
                        const isNext = label.includes('Next');

                        return (
                            <Link
                                key={i}
                                href={link.url ?? '#'}
                                preserveScroll
                                className={`flex items-center justify-center rounded-md border transition ${isPageNumber ? 'h-9 w-9 text-sm font-medium' : 'h-9 px-3 text-sm'} ${link.active ? 'bg-secondary text-primary-foreground' : 'bg-card hover:bg-accent'} ${!link.url ? 'pointer-events-none opacity-50' : ''} `}
                            >
                                {isPrevious && (
                                    <ChevronLeft className="h-4 w-4" />
                                )}
                                {isNext && <ChevronRight className="h-4 w-4" />}
                                {isPageNumber && label}
                            </Link>
                        );
                    })}
                </div>
            )}
        </>
    );
}
