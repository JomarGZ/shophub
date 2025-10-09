import { Link } from '@inertiajs/react';
import { Facebook, Instagram, Mail, Twitter } from 'lucide-react';
import { Container } from './container';

export function AppFooter() {
    return (
        <Container
            as="footer"
            className="mt-auto bg-secondary px-4 py-12 text-secondary-foreground"
        >
            <div className="grid grid-cols-1 gap-8 md:grid-cols-4">
                {/* About */}
                <div>
                    <h3 className="mb-4 text-lg font-bold">About ShopHub</h3>
                    <p className="text-sm opacity-90">
                        Your trusted online shopping destination for quality
                        products at competitive prices.
                    </p>
                </div>

                {/* Quick Links */}
                <div>
                    <h3 className="mb-4 text-lg font-bold">Quick Links</h3>
                    <ul className="space-y-2 text-sm">
                        <li>
                            <Link
                                href="/shop"
                                className="opacity-90 transition-opacity hover:opacity-100"
                            >
                                Shop
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/cart"
                                className="opacity-90 transition-opacity hover:opacity-100"
                            >
                                Cart
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/"
                                className="opacity-90 transition-opacity hover:opacity-100"
                            >
                                About Us
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/"
                                className="opacity-90 transition-opacity hover:opacity-100"
                            >
                                Contact
                            </Link>
                        </li>
                    </ul>
                </div>

                {/* Customer Service */}
                <div>
                    <h3 className="mb-4 text-lg font-bold">Customer Service</h3>
                    <ul className="space-y-2 text-sm">
                        <li className="opacity-90">Shipping Information</li>
                        <li className="opacity-90">Returns & Exchanges</li>
                        <li className="opacity-90">Terms & Conditions</li>
                        <li className="opacity-90">Privacy Policy</li>
                    </ul>
                </div>

                {/* Social */}
                <div>
                    <h3 className="mb-4 text-lg font-bold">Follow Us</h3>
                    <div className="flex gap-3">
                        <a
                            href="#"
                            className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 transition-colors hover:bg-primary/30"
                        >
                            <Facebook className="h-5 w-5" />
                        </a>
                        <a
                            href="#"
                            className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 transition-colors hover:bg-primary/30"
                        >
                            <Twitter className="h-5 w-5" />
                        </a>
                        <a
                            href="#"
                            className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 transition-colors hover:bg-primary/30"
                        >
                            <Instagram className="h-5 w-5" />
                        </a>
                        <a
                            href="#"
                            className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 transition-colors hover:bg-primary/30"
                        >
                            <Mail className="h-5 w-5" />
                        </a>
                    </div>
                </div>
            </div>
            <div className="mt-8 border-t border-secondary-foreground/20 pt-8 text-center text-sm opacity-90">
                <p>&copy; 2024 ShopHub. All rights reserved.</p>
            </div>
        </Container>
    );
}
