export interface ShopFilters {
    search?: string;
    categories?: string[];
    min_price?: number | null;
    max_price?: number | null;
}

export interface PriceRange {
    min: number;
    max: number;
}
