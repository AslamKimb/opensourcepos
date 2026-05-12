import { useEffect } from 'react';

function findMatches<T extends HTMLElement>(root: ParentNode, selector: string): T[] {
    const matches = Array.from(root.querySelectorAll<T>(selector));

    if (root instanceof HTMLElement && root.matches(selector)) {
        matches.unshift(root as T);
    }

    return matches;
}

function enhanceModalSurface(root: ParentNode = document): void {
    findMatches<HTMLElement>(root, '.modal-dialog, .bootstrap-dialog').forEach((dialog) => {
        dialog.classList.add('op-modal-shell');
    });

    findMatches<HTMLElement>(root, '.modal-content').forEach((content) => {
        content.classList.add('op-modal-shell__content');
    });

    findMatches<HTMLElement>(root, '.modal-header').forEach((header) => {
        header.classList.add('op-modal-shell__header');
    });

    findMatches<HTMLElement>(root, '.modal-body').forEach((body) => {
        body.classList.add('op-modal-shell__body');
    });

    findMatches<HTMLElement>(root, '.modal-body form, .modal-content form, .modal-body .form-horizontal, .modal-content .form-horizontal').forEach((form) => {
        form.classList.add('op-modal-shell__form');
    });

    findMatches<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>(root, '.modal-content .form-control, .modal-content .selectpicker').forEach((control) => {
        control.classList.add('op-modal-shell__control');
    });

    findMatches<HTMLButtonElement | HTMLAnchorElement>(root, '.modal-content .btn').forEach((button) => {
        button.classList.add('op-modal-shell__button');
    });
}

export function LegacyModalObserver(): null {
    useEffect(() => {
        enhanceModalSurface(document);

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node instanceof HTMLElement) {
                        enhanceModalSurface(node);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });

        return () => {
            observer.disconnect();
        };
    }, []);

    return null;
}
