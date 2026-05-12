import { BarChart3, Database, PanelTop, Rows3, SlidersHorizontal } from 'lucide-react';
import { useLayoutEffect, useMemo, useRef } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardAction, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';

type LegacyManagementIds = {
    titleBarId?: string;
    toolbarId?: string;
    tableHolderId?: string;
    summaryId?: string;
};

export type ManagementPageProps = {
    resource: string;
    title: string;
    description?: string;
    uniqueId?: string;
    pageSize?: number;
    headers?: unknown[];
    legacy?: LegacyManagementIds;
};

type HostMap = {
    titleBar: HTMLDivElement | null;
    toolbar: HTMLDivElement | null;
    tableHolder: HTMLDivElement | null;
    summary: HTMLDivElement | null;
};

const DEFAULT_LEGACY_IDS: Required<LegacyManagementIds> = {
    titleBarId: 'title_bar',
    toolbarId: 'toolbar',
    tableHolderId: 'table_holder',
    summaryId: 'payment_summary',
};

function appendLegacyNode(root: HTMLElement, host: HTMLElement | null, id: string): boolean {
    const node = root.ownerDocument.getElementById(id);

    if (!node || !host || node === host || host.contains(node)) {
        return false;
    }

    node.classList.add('op-workbench__legacy-node');
    host.appendChild(node);

    return true;
}

function enhanceLegacyButtons(root: HTMLElement): void {
    root.querySelectorAll<HTMLButtonElement | HTMLAnchorElement>('.btn').forEach((button) => {
        button.classList.add('op-workbench__button');
    });

    root.querySelectorAll<HTMLInputElement | HTMLSelectElement>('.form-control, .selectpicker').forEach((control) => {
        control.classList.add('op-workbench__control');
    });
}

export function ManagementPage({
    resource,
    title,
    description,
    uniqueId,
    pageSize,
    headers = [],
    legacy = {},
}: ManagementPageProps): React.JSX.Element {
    const rootRef = useRef<HTMLDivElement | null>(null);
    const hostsRef = useRef<HostMap>({
        titleBar: null,
        toolbar: null,
        tableHolder: null,
        summary: null,
    });

    const legacyIds = useMemo(
        () => ({
            ...DEFAULT_LEGACY_IDS,
            ...legacy,
        }),
        [legacy],
    );

    useLayoutEffect(() => {
        const root = rootRef.current;

        if (!root) {
            return undefined;
        }

        const rootWindow = root.ownerDocument.defaultView;

        root.ownerDocument.body.classList.add('ospos-management-react');

        const adoptLegacyNodes = () => {
            appendLegacyNode(root, hostsRef.current.titleBar, legacyIds.titleBarId);
            appendLegacyNode(root, hostsRef.current.toolbar, legacyIds.toolbarId);
            appendLegacyNode(root, hostsRef.current.tableHolder, legacyIds.tableHolderId);
            appendLegacyNode(root, hostsRef.current.summary, legacyIds.summaryId);
            enhanceLegacyButtons(root);
        };

        adoptLegacyNodes();
        const bootstrapTableTimeout = rootWindow?.setTimeout(adoptLegacyNodes, 0);
        const settledTableTimeout = rootWindow?.setTimeout(adoptLegacyNodes, 500);

        return () => {
            if (bootstrapTableTimeout) {
                rootWindow?.clearTimeout(bootstrapTableTimeout);
            }

            if (settledTableTimeout) {
                rootWindow?.clearTimeout(settledTableTimeout);
            }

            root.ownerDocument.body.classList.remove('ospos-management-react');
        };
    }, [legacyIds]);

    return (
        <Card ref={rootRef} className="op-workbench gap-0 overflow-hidden rounded-lg border bg-card py-0 font-sans shadow-sm" data-resource={resource}>
            <CardHeader className="op-workbench__header rounded-none border-0">
                <div className="op-workbench__title-block">
                    <Badge variant="secondary" className="op-workbench__eyebrow">
                        <PanelTop data-icon="inline-start" aria-hidden="true" />
                        {resource || 'management'}
                    </Badge>
                    <CardTitle>
                        <h1>{title}</h1>
                    </CardTitle>
                    {description ? <CardDescription>{description}</CardDescription> : null}
                </div>
                <CardAction className="op-workbench__stats" aria-label="Table configuration">
                    <Badge variant="outline">
                        <Database data-icon="inline-start" aria-hidden="true" />
                        {resource || 'management'}
                    </Badge>
                    <Badge variant="outline">
                        <Rows3 data-icon="inline-start" aria-hidden="true" />
                        {headers.length} fields
                    </Badge>
                    {pageSize ? (
                        <Badge variant="outline">
                            <BarChart3 data-icon="inline-start" aria-hidden="true" />
                            {pageSize} rows
                        </Badge>
                    ) : null}
                </CardAction>
            </CardHeader>

            <Separator />

            <CardContent className="p-0">
                <div className="op-workbench__command-row">
                    <div className="op-workbench__command-label">Actions</div>
                    <div
                        ref={(element) => {
                            hostsRef.current.titleBar = element;
                        }}
                        className="op-workbench__actions"
                    />
                </div>

                <div className="op-workbench__filters">
                    <div className="op-workbench__filter-label">
                        <SlidersHorizontal data-icon="inline-start" aria-hidden="true" />
                        Filters and bulk tools
                    </div>
                    <div
                        ref={(element) => {
                            hostsRef.current.toolbar = element;
                        }}
                        className="op-workbench__toolbar"
                    />
                </div>

                <div
                    ref={(element) => {
                        hostsRef.current.summary = element;
                    }}
                    className="op-workbench__summary"
                />

                <ScrollArea className="op-workbench__table-frame">
                    <div
                        ref={(element) => {
                            hostsRef.current.tableHolder = element;
                        }}
                        data-unique-id={uniqueId}
                    />
                    <ScrollBar orientation="horizontal" />
                </ScrollArea>
            </CardContent>
        </Card>
    );
}
