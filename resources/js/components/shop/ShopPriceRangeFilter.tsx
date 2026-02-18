import { Slider } from '@/components/ui/slider';

interface Props {
    value: number[];
    min: number;
    max: number;
    onChange: (value: number[]) => void;
}

export function ShopPriceRangeFilter({ value, min, max, onChange }: Props) {
    return (
        <>
            <Slider
                min={min}
                max={max}
                step={10}
                value={value}
                onValueChange={onChange}
            />

            <div className="flex justify-between text-sm">
                <span>${value[0]}</span>
                <span>${value[1]}</span>
            </div>
        </>
    );
}
