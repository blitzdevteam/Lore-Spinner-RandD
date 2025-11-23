import React, { type ReactNode } from 'react';
import NavigationMenu from '@/components/ui/navigationMenu';
import FooterBoxMenu from '@/components/ui/footerBoxMenu';

type AppLayoutProps = {
    children: ReactNode;
};

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <div className="relative min-h-screen text-white">
            {/* Background gradient and subtle pattern layers */}
            <div className="pointer-events-none absolute inset-0 ls-landing-gradient" />
            <div className="pointer-events-none absolute inset-0 ls-landing-lights" />
            <div className="pointer-events-none absolute inset-0 ls-landing-grid" />

            <div className="relative">
                <NavigationMenu />
                {children}
                <FooterBoxMenu />
            </div>
        </div>
    );
}


