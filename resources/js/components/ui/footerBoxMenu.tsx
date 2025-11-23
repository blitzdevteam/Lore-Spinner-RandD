import React from "react";
import { Link } from "@inertiajs/react";

type FooterBoxMenuProps = {
    logoSrc?: string;
};

export default function FooterBoxMenu({ logoSrc = "/logo.svg" }: FooterBoxMenuProps) {
    return (
        <footer className="mx-auto my-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="rounded-2xl border border-white/10 bg-[#0f1016]/90 p-8 text-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] backdrop-blur md:p-12">
                <div className="grid gap-10 md:grid-cols-12 md:items-start">
                    {/* Brand + intro */}
                    <div className="md:col-span-4">
                        <div className="text-2xl font-extrabold tracking-wide">LORE SPINNER</div>
                        <p className="mt-5 max-w-sm text-sm leading-relaxed text-white/70">
                            Your quest for the perfect D&D experience starts here—with AI by your side.
                        </p>
                        <div className="mt-5 flex items-center gap-4 text-white/70">
                            {/* Simple inline social icons */}
                            <a aria-label="Twitter" href="#" className="transition hover:text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.48 8.48 0 0 1-2.7 1.03 4.24 4.24 0 0 0-7.22 3.87A12.04 12.04 0 0 1 3.16 4.9a4.24 4.24 0 0 0 1.31 5.66c-.64-.02-1.24-.2-1.77-.49v.05a4.24 4.24 0 0 0 3.4 4.16c-.3.08-.63.12-.96.12-.23 0-.47-.02-.69-.07a4.24 4.24 0 0 0 3.96 2.94A8.5 8.5 0 0 1 2 19.54 12.02 12.02 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.55A8.35 8.35 0 0 0 22.46 6z"/></svg>
                            </a>
                            <a aria-label="Facebook" href="#" className="transition hover:text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07C2 17.06 5.66 21.2 10.44 22v-7.02H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22C18.34 21.2 22 17.06 22 12.07z"/></svg>
                            </a>
                            <a aria-label="Instagram" href="#" className="transition hover:text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 2A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5zM17.75 6a.75.75 0 1 0 .75.75A.75.75 0 0 0 17.75 6z"/></svg>
                            </a>
                            <a aria-label="LinkedIn" href="#" className="transition hover:text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3A2.94 2.94 0 0 1 22 6v12a2.94 2.94 0 0 1-3 3H5a2.94 2.94 0 0 1-3-3V6a2.94 2.94 0 0 1 3-3h14zM8.34 18.34V9.85H5.88v8.49h2.46zM7.11 8.67a1.42 1.42 0 1 0 0-2.84 1.42 1.42 0 0 0 0 2.84zM18.12 18.34v-4.6c0-2.46-1.31-3.6-3.06-3.6a2.65 2.65 0 0 0-2.39 1.31h-.03V9.85h-2.46c.03.73 0 8.49 0 8.49h2.46v-4.74c0-.25.02-.49.09-.67.2-.49.64-1 1.41-1 1 0 1.4.75 1.4 1.85v4.56h2.58z"/></svg>
                            </a>
                        </div>
                    </div>

                    {/* Link columns */}
                    <div className="md:col-span-5 grid grid-cols-2 gap-8 sm:grid-cols-3">
                        <div>
                            <div className="text-sm font-semibold tracking-wide text-white/60">NAVIGATE</div>
                            <ul className="mt-4 space-y-3 text-sm">
                                <li><Link href="/" className="text-white/80 transition hover:text-white">Home</Link></li>
                                <li><Link href="/about" className="text-white/80 transition hover:text-white">About</Link></li>
                                <li><Link href="/investors" className="text-white/80 transition hover:text-white">Investors</Link></li>
                                <li><Link href="/contact" className="text-white/80 transition hover:text-white">Contact</Link></li>
                            </ul>
                        </div>
                        <div>
                            <div className="text-sm font-semibold tracking-wide text-white/60">LEARN</div>
                            <ul className="mt-4 space-y-3 text-sm">
                                <li><Link href="/about" className="text-white/80 transition hover:text-white">Features</Link></li>
                                <li><Link href="/investors" className="text-white/80 transition hover:text-white">Events</Link></li>
                                <li><Link href="/about" className="text-white/80 transition hover:text-white">About</Link></li>
                            </ul>
                        </div>
                        <div>
                            <div className="text-sm font-semibold tracking-wide text-white/60">CONNECT</div>
                            <ul className="mt-4 space-y-3 text-sm">
                                <li><Link href="/contact" className="text-white/80 transition hover:text-white">Contact page</Link></li>
                                <li><a href="mailto:investors@lorespinner.com" className="text-white/80 transition hover:text-white">Email us</a></li>
                            </ul>
                        </div>
                    </div>

                    {/* Logo */}
                    <div className="md:col-span-3 flex items-center justify-end">
                        <img src={logoSrc} alt="Lore Spinner logo" className="h-32 w-32 md:h-40 md:w-40 object-contain" />
                    </div>
                </div>
            </div>
        </footer>
    );
}
