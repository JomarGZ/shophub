import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Category } from '@/types';
import { ShopCategoryFilter } from './ShopCategoryFilter';
import { ShopPriceRangeFilter } from './ShopPriceRangeFilter';

interface Props {
    categories: Category[];
    selectedCategories: string[];
    toggleCategory: (slug: string) => void;
    priceRange: number[];
    setPriceRange: (v: number[]) => void;
    serverPriceRange: { min: number; max: number };
    reset: () => void;
}

export function ShopFiltersSidebar(props: Props) {
    return (
        <div className="sticky top-24 rounded-lg bg-card p-6 shadow-card">
            <h2 className="mb-6 text-xl font-bold">Filters</h2>

            <Label>Categories</Label>
            <ShopCategoryFilter
                categories={props.categories}
                selected={props.selectedCategories}
                toggle={props.toggleCategory}
            />

            <Label className="mt-6">Price Range</Label>
            <ShopPriceRangeFilter
                value={props.priceRange}
                min={props.serverPriceRange.min}
                max={props.serverPriceRange.max}
                onChange={props.setPriceRange}
            />

            <Button className="mt-6 w-full" onClick={props.reset}>
                Reset Filters
            </Button>
        </div>
    );
}
