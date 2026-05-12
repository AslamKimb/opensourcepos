import { createRoot, type Root } from 'react-dom/client';
import { HomeModules, type HomeModulesProps } from './home/HomeModules';
import { LegacyAutoShell, type LegacyAutoShellProps } from './legacy/LegacyAutoShell';
import { LoginPage, type LoginPageProps } from './login/LoginPage';
import { ManagementPage, type ManagementPageProps } from './management/ManagementPage';
import { ShellNavigation, type ShellNavigationProps } from './shell/ShellNavigation';
import { TabbedShell, type TabbedShellProps } from './tabs/TabbedShell';

const mountedRoots = new WeakMap<Element, Root>();

function readJsonProps<T>(rootDocument: Document, propsId: string | undefined): T | null {
    if (!propsId) {
        return null;
    }

    const propsElement = rootDocument.getElementById(propsId);

    if (!propsElement?.textContent) {
        return null;
    }

    try {
        return JSON.parse(propsElement.textContent) as T;
    } catch {
        return null;
    }
}

function renderRoot(element: Element, node: React.ReactNode): void {
    const root = mountedRoots.get(element) ?? createRoot(element);
    mountedRoots.set(element, root);
    root.render(node);
}

export function mountReactIslands(rootDocument: Document = document): void {
    rootDocument.querySelectorAll<HTMLElement>('[data-react-root]').forEach((element) => {
        if (element.dataset.reactRoot === 'home-modules') {
            const props = readJsonProps<HomeModulesProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <HomeModules {...props} />);
            return;
        }

        if (element.dataset.reactRoot === 'login-page') {
            const props = readJsonProps<LoginPageProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <LoginPage {...props} />);
            return;
        }

        if (element.dataset.reactRoot === 'shell-navigation') {
            const props = readJsonProps<ShellNavigationProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <ShellNavigation {...props} />);
            return;
        }

        if (element.dataset.reactRoot === 'management-page') {
            const props = readJsonProps<ManagementPageProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <ManagementPage {...props} />);
            return;
        }

        if (element.dataset.reactRoot === 'legacy-auto-shell') {
            const props = readJsonProps<LegacyAutoShellProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <LegacyAutoShell {...props} />);
            return;
        }

        if (element.dataset.reactRoot === 'tabbed-shell') {
            const props = readJsonProps<TabbedShellProps>(rootDocument, element.dataset.propsId);

            if (!props) {
                return;
            }

            renderRoot(element, <TabbedShell {...props} />);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => mountReactIslands());
} else {
    mountReactIslands();
}
