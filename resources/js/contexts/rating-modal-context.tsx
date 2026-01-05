import { createContext, useContext, useState } from 'react';

type ItemToRate = {
    productName: string;
    productSlug: string;
};

type RatingModalContextType = {
    isOpen: boolean;
    itemToRate: ItemToRate | null;
    open: (item: ItemToRate) => void;
    close: () => void;
};

const RatingModalContext = createContext<RatingModalContextType | undefined>(
    undefined,
);

export function RatingModalProvider({
    children,
}: {
    children: React.ReactNode;
}) {
    const [isOpen, setIsOpen] = useState<boolean>(false);
    const [itemToRate, setItemToRate] = useState<ItemToRate | null>(null);

    const open = (item: ItemToRate) => {
        if (!item || !item.productSlug) {
            console.warn('Invalid item provided to open rating modal.');
            return;
        }
        setItemToRate(item);
        setIsOpen(true);
    };

    const close = () => {
        setItemToRate(null);
        setIsOpen(false);
    };

    return (
        <RatingModalContext.Provider
            value={{ isOpen, itemToRate, open, close }}
        >
            {children}
        </RatingModalContext.Provider>
    );
}

export function useRatingModal() {
    const context = useContext(RatingModalContext);
    if (context === undefined) {
        throw new Error(
            'useRatingModal must be used within a RatingModalProvider',
        );
    }
    return context;
}
