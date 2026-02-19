import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Category } from '@/types';
import { ShopCategoryFilter } from './ShopCategoryFilter';
import { ShopPriceRangeFilter } from './ShopPriceRangeFilter';

interface Props {
    categories: Category[];
    values: {
        categories: string[];
        min_price: number;
        max_price: number;
    };
    priceRange: {
        min: number;
        max: number;
    };
    onChange: (name: string, value: any) => void;
    onReset: () => void;
}
export function ShopFiltersSidebar({
    categories,
    values,
    priceRange,
    onChange,
    onReset,
}: Props) {
    return (
        <div className="sticky top-24 rounded-lg bg-card p-6 shadow-card">
            <h2 className="mb-6 text-xl font-bold">Filters</h2>

            <Label>Categories</Label>
            <ShopCategoryFilter
                categories={categories}
                values={values.categories}
                onChange={onChange}
            />

            <Label className="mt-6">Price Range</Label>
            <ShopPriceRangeFilter
                value={[values.min_price, values.max_price]}
                min={priceRange.min}
                max={priceRange.max}
                onChange={(range: number[]) => {
                    onChange('min_price', range[0]);
                    onChange('max_price', range[1]);
                }}
            />

            <Button onClick={onReset} className="mt-6 w-full">
                Reset Filters
            </Button>
        </div>
    );
}
