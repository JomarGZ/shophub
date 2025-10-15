import { ShoppingCart } from 'lucide-react';

export default function AppLogo() {
    return (
        <>
            <div className="bg-gradient-hero flex h-10 w-10 items-center justify-center rounded-lg">
                <ShoppingCart className="h-6 w-6 text-white" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-lg">
                <span className="mb-0.5 truncate leading-tight font-semibold">
                    ShopHub
                </span>
            </div>
        </>
    );
}
