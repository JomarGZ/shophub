import { Checkbox } from '@/components/ui/checkbox';
import { Category } from '@/types';

interface Props {
    categories: Category[];
    selected: string[];
    toggle: (slug: string) => void;
}

export function ShopCategoryFilter({ categories, selected, toggle }: Props) {
    return (
        <div className="space-y-3">
            {categories.map((category) => (
                <div key={category.slug} className="flex gap-2">
                    <Checkbox
                        id={`cat-${category.slug}`}
                        checked={selected.includes(category.slug)}
                        onCheckedChange={() => toggle(category.slug)}
                    />
                    <label htmlFor={`cat-${category.slug}`}>
                        {category.name}
                    </label>
                </div>
            ))}
        </div>
    );
}
