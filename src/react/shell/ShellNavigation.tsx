import { Menu, X } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';

export type ShellModule = {
    id: string;
    name: string;
    url: string;
    iconUrl: string;
    active: boolean;
};

export type ShellNavigationProps = {
    company: string;
    currentTime: string;
    userName: string;
    changePasswordUrl: string;
    logoutUrl: string;
    homeUrl: string;
    modules: ShellModule[];
};

export function ShellNavigation({
    company,
    currentTime,
    userName,
    changePasswordUrl,
    logoutUrl,
    homeUrl,
    modules,
}: ShellNavigationProps) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <header className="op-shell">
            <div className="op-shell__topbar">
                <div id="liveclock">{currentTime}</div>
                <strong>{company}</strong>
                <nav aria-label="Account">
                    <a className="modal-dlg" href={changePasswordUrl} title="Change password">
                        {userName}
                    </a>
                    <span aria-hidden="true">|</span>
                    <a href={logoutUrl}>Logout</a>
                </nav>
            </div>

            <div className="op-shell__nav">
                <a className="op-shell__brand" href={homeUrl}>
                    OSPOS
                </a>
                <Button
                    className="op-shell__toggle"
                    type="button"
                    variant="outline"
                    size="icon"
                    aria-expanded={isOpen}
                    aria-controls="op-shell-modules"
                    aria-label="Toggle navigation"
                    onClick={() => setIsOpen((open) => !open)}
                >
                    {isOpen ? <X aria-hidden="true" /> : <Menu aria-hidden="true" />}
                </Button>
                <nav id="op-shell-modules" className={isOpen ? 'op-shell__modules is-open' : 'op-shell__modules'} aria-label="Modules">
                    {modules.map((module) => (
                        <a
                            className={module.active ? 'op-shell__module is-active' : 'op-shell__module'}
                            href={module.url}
                            title={module.name}
                            key={module.id}
                        >
                            <img src={module.iconUrl} alt="" aria-hidden="true" />
                            <span>{module.name}</span>
                        </a>
                    ))}
                </nav>
            </div>
        </header>
    );
}
