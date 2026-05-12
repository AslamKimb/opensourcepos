import { ShieldCheck } from 'lucide-react';
import { useLayoutEffect, useRef } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';

export type LoginPageProps = {
    company: string;
    product: string;
    logoSelector?: string;
    formSelector?: string;
    footerSelector?: string;
};

function adoptNode(root: HTMLElement, host: HTMLElement | null, selector: string): void {
    const node = root.ownerDocument.querySelector<HTMLElement>(selector);

    if (node && host && !host.contains(node)) {
        host.appendChild(node);
    }
}

export function LoginPage({
    company,
    product,
    logoSelector = '#login-logo-source',
    formSelector = '#login-form-source',
    footerSelector = '#login-footer-source',
}: LoginPageProps) {
    const rootRef = useRef<HTMLDivElement | null>(null);
    const logoHostRef = useRef<HTMLDivElement | null>(null);
    const formHostRef = useRef<HTMLDivElement | null>(null);
    const footerHostRef = useRef<HTMLDivElement | null>(null);

    useLayoutEffect(() => {
        const root = rootRef.current;

        if (!root) {
            return;
        }

        adoptNode(root, logoHostRef.current, logoSelector);
        adoptNode(root, formHostRef.current, formSelector);
        adoptNode(root, footerHostRef.current, footerSelector);

        root.ownerDocument.querySelector<HTMLElement>('#login-legacy-source')?.setAttribute('hidden', 'hidden');
    }, [footerSelector, formSelector, logoSelector]);

    return (
        <main ref={rootRef} className="op-login">
            <Card className="op-login__card rounded-lg border bg-card py-0 shadow-lg">
                <CardContent className="op-login__content p-0">
                    <section className="op-login__brand-panel" aria-label={company}>
                        <Badge variant="secondary" className="op-login__badge">
                            <ShieldCheck data-icon="inline-start" aria-hidden="true" />
                            Secure point of sale
                        </Badge>
                        <div ref={logoHostRef} className="op-login__logo-host" />
                        <h1>{company}</h1>
                        <p>{product}</p>
                    </section>
                    <section className="op-login__form-panel" aria-label="Login form">
                        <div ref={formHostRef} className="op-login__form-host" />
                    </section>
                </CardContent>
            </Card>
            <div ref={footerHostRef} className="op-login__footer-host" />
        </main>
    );
}
