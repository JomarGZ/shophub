import { Input } from '@/components/ui/input';
import { Search } from 'lucide-react';

interface Props {
    value: string;
    onChange: (value: string) => void;
}
export function ShopSearchBar({ value, onChange }: Props) {
    return (
        <div className="relative">
            <Search className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
            <Input
                name="search"
                autoComplete="off"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                placeholder="Search products..."
                className="h-12 pl-11"
            />
        </div>
    );
}
