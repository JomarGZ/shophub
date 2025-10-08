import { login, register } from '@/routes';
import { Link } from '@inertiajs/react';
import { LogIn, UserCheck } from 'lucide-react';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from './ui/dropdown-menu';

export function AuthMenuContent() {
    return (
        <>
            <DropdownMenuGroup>
                <DropdownMenuItem asChild>
                    <Link className="block w-full" href={login()} as="button">
                        <LogIn className="mr-2" />
                        Login
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
            <DropdownMenuItem asChild>
                <Link className="block w-full" href={register()} as="button">
                    <UserCheck className="mr-2" />
                    Signup
                </Link>
            </DropdownMenuItem>
        </>
    );
}
