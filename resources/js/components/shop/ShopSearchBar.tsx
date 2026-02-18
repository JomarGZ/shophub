import { Input } from '@/components/ui/input';
import { index } from '@/routes/shop';
import { router, usePage } from '@inertiajs/react';
import pickBy from 'lodash.pickby';
import { Search } from 'lucide-react';
import React, { useEffect, useState } from 'react';
import { usePrevious } from 'react-use';
import { useDebounce } from 'use-debounce';
export function ShopSearchBar() {
    const { filters } = usePage<{
        filters: { search?: string };
    }>().props;

    const [values, setValues] = useState({
        search: filters.search || '',
    });

    const handleFilterChange = (
        values: Record<string, any>,
        prevValues: Record<string, any> | undefined,
    ) => {
        if (!prevValues) return;
        const query = Object.keys(pickBy(values)).length ? pickBy(values) : {};

        router.get(index.url(), query, {
            replace: true,
            preserveState: true,
        });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const name = e.target.name;
        const value = e.target.value;
        setValues((values) => ({
            ...values,
            [name]: value,
        }));
    };

    const prevValues = usePrevious(values);
    const [debounceValues] = useDebounce(values, 500);

    useEffect(() => {
        handleFilterChange(debounceValues, prevValues);
    }, [debounceValues]);

    return (
        <div className="relative">
            <Search className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
            <Input
                name="search"
                autoComplete="off"
                value={values.search}
                onChange={handleChange}
                placeholder="Search products..."
                className="h-12 pl-11"
            />
        </div>
    );
}
