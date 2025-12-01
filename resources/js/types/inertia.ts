import { PageProps as InertiaPageProps } from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps {
        flash: {
            success?: string | null;
            error?: string | null;
        };
    }
}
