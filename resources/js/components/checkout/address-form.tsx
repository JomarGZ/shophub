import AddressController from '@/actions/App/Http/Controllers/AddressController';
import { useForm } from '@inertiajs/react';
import axios from 'axios';
import { LoaderCircle } from 'lucide-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { useDebounce } from 'use-debounce';
import InputError from '../input-error';
import { Button } from '../ui/button';
import {
    Command,
    CommandEmpty,
    CommandInput,
    CommandItem,
    CommandList,
} from '../ui/command';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '../ui/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '../ui/select';

type City = {
    id: number;
    name: string;
};

interface Country {
    id: number;
    name: string;
}
interface AddressFormProps {
    countries: Country[];
    onCancel: () => void;
}
export function AddressForm({ countries, onCancel }: AddressFormProps) {
    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(false);
    const [cities, setCities] = useState<City[]>([]);
    const [open, setOpen] = useState(false);
    const controllerForm = AddressController.store.form();
    const [debounceSearch] = useDebounce(search, 500);
    const form = useForm({
        country_id: '',
        city_id: '',
        first_name: '',
        last_name: '',
        phone: '',
        street_address: '',
    });
    const { data, setData, processing, errors, post } = form;
    const fetchCities = async (query = '', country_id: number) => {
        if (!data.country_id) return;
        setLoading(true);
        try {
            const { data: res } = await axios.get('city/list', {
                params: {
                    country_id: country_id,
                    search: query,
                    limit: query ? 20 : 100,
                },
            });
            setCities(res.success && Array.isArray(res.data) ? res.data : []);
        } catch (error) {
            console.error('Error fetching cities:', error);
            setCities([]);
        } finally {
            setLoading(false);
        }
    };
    const handleSubmit = (e: { preventDefault: () => void }) => {
        e.preventDefault();
        post(controllerForm.action, {
            preserveScroll: true,
            onSuccess: ({ props: { flash } }: any) => {
                toast.success(flash.message);
            },
        });
    };
    useEffect(() => {
        fetchCities(debounceSearch, Number(data.country_id));
    }, [debounceSearch, data.country_id]);
    return (
        <form
            onSubmit={handleSubmit}
            method={controllerForm.method}
            className="mt-4 rounded-lg border border-border bg-muted/30 p-4"
        >
            <h3 className="mb-4 font-semibold text-foreground">New Address</h3>
            <div className="space-y-4">
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label htmlFor="first_ame">First Name *</Label>
                        <Input
                            id="first_ame"
                            type="text"
                            value={data.first_name}
                            onChange={(e) =>
                                setData('first_name', e.target.value)
                            }
                            autoComplete="first_name"
                            name="first_name"
                        />
                        <InputError
                            message={errors.first_name}
                            className="mt-2"
                        />
                    </div>
                    <div>
                        <Label htmlFor="last_name">Last Name *</Label>
                        <Input
                            id="last_name"
                            type="text"
                            value={data.last_name}
                            onChange={(e) =>
                                setData('last_name', e.target.value)
                            }
                            autoComplete="last_name"
                            name="last_name"
                        />
                        <InputError
                            message={errors.last_name}
                            className="mt-2"
                        />
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label htmlFor="country_id">Country *</Label>
                        <Select
                            value={String(data.country_id) || ''}
                            onValueChange={(value) =>
                                setData('country_id', value)
                            }
                        >
                            <SelectTrigger className="w-full" id="country_id">
                                <SelectValue placeholder="Select Country" />
                            </SelectTrigger>
                            <SelectContent>
                                {countries.length > 0 &&
                                    countries.map((country) => (
                                        <SelectItem
                                            key={country.id}
                                            value={String(country.id)}
                                        >
                                            {country.name}
                                        </SelectItem>
                                    ))}
                            </SelectContent>
                        </Select>
                        <InputError
                            message={errors.country_id}
                            className="mt-2"
                        />
                    </div>
                    <div>
                        <Label>City *</Label>
                        <Popover open={open} onOpenChange={setOpen}>
                            <PopoverTrigger asChild>
                                <Button
                                    variant="outline"
                                    role="combobox"
                                    className="w-full justify-between"
                                >
                                    {data.city_id
                                        ? cities.find(
                                              (city) =>
                                                  city.id ===
                                                  Number(data.city_id),
                                          )?.name || 'Select city'
                                        : 'Select city'}
                                </Button>
                            </PopoverTrigger>
                            <PopoverContent className="w-[300px] p-0">
                                <Command shouldFilter={false}>
                                    <CommandInput
                                        placeholder="Search city..."
                                        value={search}
                                        onValueChange={setSearch}
                                    />
                                    <CommandList>
                                        <CommandEmpty>
                                            {loading
                                                ? 'Loading...'
                                                : 'No cities found.'}
                                        </CommandEmpty>
                                        {!loading &&
                                            cities.map((city) => (
                                                <CommandItem
                                                    key={city.id}
                                                    value={String(city.id)}
                                                    onSelect={() => {
                                                        setData(
                                                            'city_id',
                                                            String(city.id),
                                                        );
                                                        setOpen(false);
                                                    }}
                                                >
                                                    {city.name}
                                                </CommandItem>
                                            ))}
                                    </CommandList>
                                </Command>
                            </PopoverContent>
                        </Popover>
                        <InputError message={errors.city_id} className="mt-2" />
                    </div>
                </div>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label htmlFor="phone">Phone *</Label>
                        <Input
                            id="phone"
                            type="tel"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            autoComplete="tel"
                            name="phone"
                        />
                        <InputError message={errors.phone} className="mt-2" />
                    </div>
                    <div>
                        <Label htmlFor="street_address">Street Address *</Label>
                        <Input
                            id="street_address"
                            type="text"
                            value={data.street_address}
                            onChange={(e) =>
                                setData('street_address', e.target.value)
                            }
                            autoComplete="street_address"
                            name="street_address"
                        />
                        <InputError message={errors.street_address} />
                    </div>
                </div>

                <div className="flex gap-2">
                    <Button
                        type="submit"
                        disabled={processing}
                        className="bg-primary text-primary-foreground hover:bg-primary/90"
                    >
                        {processing && (
                            <LoaderCircle className="h-4 w-4 animate-spin" />
                        )}
                        Save Address
                    </Button>
                    <Button type="button" variant="outline" onClick={onCancel}>
                        Cancel
                    </Button>
                </div>
            </div>
        </form>
    );
}
