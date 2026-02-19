import { Checkbox } from '@/components/ui/checkbox';
import { Category } from '@/types';

interface Props {
    categories: Category[];
    values: string[];
    onChange: (name: string, value: any) => void;
}

export function ShopCategoryFilter({ categories, values, onChange }: Props) {
    const toggle = (slug: string) => {
        const updated = values.includes(slug)
            ? values.filter((s) => s !== slug)
            : [...values, slug];
        onChange('categories', updated);
    };
    return (
        <div className="space-y-3">
            {categories.map((category) => (
                <div key={category.slug} className="flex gap-2">
                    <Checkbox
                        id={`cat-${category.slug}`}
                        checked={values.includes(category.slug)}
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
