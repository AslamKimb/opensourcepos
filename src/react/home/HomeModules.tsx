import { ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardAction, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

export type HomeModule = {
    id: string;
    name: string;
    description: string;
    url: string;
    iconUrl: string;
};

export type HomeModulesProps = {
    modules: HomeModule[];
};

export function HomeModules({ modules }: HomeModulesProps) {
    return (
        <div
            className="ospos-home-modules grid grid-cols-[repeat(auto-fit,minmax(15rem,1fr))] gap-3 my-6 font-sans"
            aria-label="Available modules"
        >
            {modules.map((module) => (
                <a
                    className="group block min-w-0 text-inherit no-underline hover:text-inherit focus:text-inherit"
                    href={module.url}
                    title={module.description}
                    key={module.id}
                >
                    <Card className="h-full min-h-28 gap-3 rounded-lg py-4 transition-colors group-hover:border-primary/40 group-focus-within:border-primary/40">
                        <CardHeader className="grid-cols-[auto_1fr_auto] items-center gap-3 px-4">
                            <img className="size-12 shrink-0" src={module.iconUrl} alt="" aria-hidden="true" />
                            <CardTitle className="min-w-0 truncate text-base">{module.name}</CardTitle>
                            <CardAction>
                                <Button asChild variant="ghost" size="icon-sm" aria-label={module.name}>
                                    <span>
                                        <ArrowRight data-icon="inline-end" aria-hidden="true" />
                                    </span>
                                </Button>
                            </CardAction>
                        </CardHeader>
                        <CardContent className="px-4">
                            <CardDescription className="line-clamp-2 text-xs leading-5">
                                {module.description}
                            </CardDescription>
                        </CardContent>
                    </Card>
                </a>
            ))}
        </div>
    );
}
