import { Settings2 } from 'lucide-react';
import { useLayoutEffect, useMemo, useRef } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';

export type TabbedShellProps = {
    title: string;
    description?: string;
    navSelector?: string;
    contentSelector?: string;
};

export function TabbedShell({
    title,
    description,
    navSelector = '.nav-tabs[data-tabs="tabs"]',
    contentSelector = '.tab-content',
}: TabbedShellProps) {
    const rootRef = useRef<HTMLDivElement | null>(null);
    const navHostRef = useRef<HTMLDivElement | null>(null);
    const contentHostRef = useRef<HTMLDivElement | null>(null);

    const selectors = useMemo(() => ({ navSelector, contentSelector }), [navSelector, contentSelector]);

    useLayoutEffect(() => {
        const root = rootRef.current;

        if (!root) {
            return;
        }

        const nav = root.ownerDocument.querySelector<HTMLElement>(selectors.navSelector);
        const content = root.ownerDocument.querySelector<HTMLElement>(selectors.contentSelector);

        if (nav && navHostRef.current && !navHostRef.current.contains(nav)) {
            nav.classList.add('op-tabs__legacy-nav');
            navHostRef.current.appendChild(nav);
        }

        if (content && contentHostRef.current && !contentHostRef.current.contains(content)) {
            content.classList.add('op-tabs__legacy-content');
            contentHostRef.current.appendChild(content);
        }
    }, [selectors]);

    return (
        <Card ref={rootRef} className="op-tabs gap-0 overflow-hidden rounded-lg border bg-card py-0 font-sans shadow-sm">
            <CardHeader className="op-tabs__header">
                <Badge variant="secondary" className="op-tabs__eyebrow">
                    <Settings2 data-icon="inline-start" aria-hidden="true" />
                    Settings
                </Badge>
                <CardTitle>
                    <h1>{title}</h1>
                </CardTitle>
                {description ? <CardDescription>{description}</CardDescription> : null}
            </CardHeader>
            <Separator />
            <CardContent className="p-0">
                <ScrollArea className="op-tabs__nav-scroll">
                    <div ref={navHostRef} className="op-tabs__nav-host" />
                    <ScrollBar orientation="horizontal" />
                </ScrollArea>
                <div ref={contentHostRef} className="op-tabs__content-host" />
            </CardContent>
        </Card>
    );
}
