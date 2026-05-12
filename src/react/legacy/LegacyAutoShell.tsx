import { PanelTop } from 'lucide-react';
import { useLayoutEffect, useMemo, useRef, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';

export type LegacyAutoShellProps = {
    title: string;
    section?: string;
};

const ADOPTABLE_NODE_SELECTOR = ':scope > *:not(script):not(style):not(template)';

function hasDedicatedReactPage(root: HTMLElement): boolean {
    const island = root.parentElement;
    const parent = island?.parentElement;

    if (!parent) {
        return true;
    }

    return Array.from(parent.querySelectorAll<HTMLElement>('[data-react-root]')).some((element) => element !== island);
}

function collectAdoptableNodes(root: HTMLElement): HTMLElement[] {
    const island = root.parentElement;
    const parent = island?.parentElement;

    if (!parent) {
        return [];
    }

    return Array.from(parent.querySelectorAll<HTMLElement>(ADOPTABLE_NODE_SELECTOR)).filter((node) => node !== island);
}

function enhanceLegacySurface(host: HTMLElement): void {
    host.querySelectorAll<HTMLButtonElement | HTMLAnchorElement>('.btn').forEach((button) => {
        button.classList.add('op-page-shell__button');
    });

    host.querySelectorAll<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>('.form-control, .selectpicker').forEach((control) => {
        control.classList.add('op-page-shell__control');
    });
}

function findPageTitle(host: HTMLElement, fallback: string): string {
    const titleNode = host.querySelector<HTMLElement>('#page_title, #header, legend, h1, h2');
    const title = titleNode?.textContent?.replace(/\s+/g, ' ').trim();

    return title || fallback;
}

export function LegacyAutoShell({ title, section }: LegacyAutoShellProps): React.JSX.Element | null {
    const rootRef = useRef<HTMLDivElement | null>(null);
    const contentHostRef = useRef<HTMLDivElement | null>(null);
    const [adopted, setAdopted] = useState(false);
    const [resolvedTitle, setResolvedTitle] = useState(title);

    const label = useMemo(() => section || title, [section, title]);

    useLayoutEffect(() => {
        const root = rootRef.current;
        const contentHost = contentHostRef.current;

        if (!root || !contentHost || hasDedicatedReactPage(root)) {
            return;
        }

        const nodes = collectAdoptableNodes(root);

        if (nodes.length === 0) {
            return;
        }

        nodes.forEach((node) => {
            node.classList.add('op-page-shell__legacy-node');
            contentHost.appendChild(node);
        });

        enhanceLegacySurface(contentHost);
        setResolvedTitle(findPageTitle(contentHost, title));
        setAdopted(true);
    }, [title]);

    return (
        <Card ref={rootRef} hidden={!adopted} className="op-page-shell gap-0 overflow-hidden rounded-lg border bg-card py-0 font-sans shadow-sm">
            <CardHeader className="op-page-shell__header">
                <Badge variant="secondary" className="op-page-shell__eyebrow">
                    <PanelTop data-icon="inline-start" aria-hidden="true" />
                    {label}
                </Badge>
                <CardTitle>
                    <h1>{resolvedTitle}</h1>
                </CardTitle>
            </CardHeader>
            <Separator />
            <CardContent className="p-0">
                <ScrollArea className="op-page-shell__content-frame">
                    <div ref={contentHostRef} className="op-page-shell__content" />
                    <ScrollBar orientation="horizontal" />
                </ScrollArea>
            </CardContent>
        </Card>
    );
}
