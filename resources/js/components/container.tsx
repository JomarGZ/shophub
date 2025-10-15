import { cn } from '@/lib/utils';
import React from 'react';

interface ContainerProps {
    children: React.ReactNode;
    className?: string;
    innerClassName?: string;
    as?: 'div' | 'section' | 'article' | 'footer';
    maxWidth?: 'md' | 'lg' | 'xl' | '2xl' | 'full';
}
export function Container({
    children,
    className,
    innerClassName,
    as: Component = 'div',
    maxWidth = 'xl',
}: ContainerProps) {
    const maxWidthClasses = {
        md: 'max-w-3xl',
        lg: 'max-w-5xl',
        xl: 'max-w-7xl',
        '2xl': 'max-w-screen-2xl',
        full: 'max-w-none',
    };
    return (
        <Component className={className}>
            <div
                className={cn(
                    'container mx-auto p-4',
                    maxWidthClasses[maxWidth],
                    innerClassName,
                )}
            >
                {children}
            </div>
        </Component>
    );
}
