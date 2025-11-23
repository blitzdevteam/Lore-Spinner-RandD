import { Link, usePage } from '@inertiajs/react';

export default function NavigationMenu() {
    const { url } = usePage();

    const items = [
        { label: 'HOME', href: '/' },
        { label: 'ABOUT', href: '/about' },
        { label: 'INVESTORS', href: '/investors' },
        { label: 'CONTACT', href: '/contact' },
    ];

    return (
        <nav className="sticky top-0 z-50 w-full bg-gradient-to-r from-[#2B3B8A] via-[#6F6E88] to-[#1F212A] text-white">
            <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div className="text-lg font-extrabold tracking-wide sm:text-xl">LORE SPINNER</div>

                <ul className="hidden items-center gap-8 md:flex">
                    {items.map((item) => {
                        const isActive = url === item.href || (item.href !== '/' && url.startsWith(item.href));
                        return (
                            <li key={item.href}>
                                <Link
                                    href={item.href}
                                    className={
                                        `text-[13px] font-semibold tracking-wide transition-colors ` +
                                        (isActive ? 'text-[#F8C57A]' : 'text-white/80 hover:text-white')
                                    }
                                >
                                    {item.label}
                                </Link>
                            </li>
                        );
                    })}
                </ul>

                <div className="hidden items-center gap-3 md:flex">
                    <Link
                        href="#"
                        className="rounded-full border border-[#F8C57A] px-4 py-1.5 text-[12px] font-semibold tracking-wide text-white transition-colors hover:bg-white/10"
                    >
                        PREMIUM
                    </Link>
                    <Link
                        href="#"
                        className="rounded-full bg-[#F8C57A] px-4 py-1.5 text-[12px] font-semibold tracking-wide text-[#1B1B18] transition-colors hover:bg-[#FFD089]"
                    >
                        AI DASHBOARD
                    </Link>
                </div>
            </div>
        </nav>
    );
}
