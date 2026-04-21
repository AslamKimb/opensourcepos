(function(global, $, bootstrap) {
    var open_dialogs = [];

    var normalize_button_class = function(cssClass) {
        if (!cssClass) {
            return 'btn-secondary';
        }

        return cssClass.replace('btn-default', 'btn-outline-secondary');
    };

    var close_dialog = function(dialog) {
        dialog.modal.hide();
    };

    var refresh_content = function(modal, body) {
        if (bootstrap.Tooltip) {
            $(body).find('[data-bs-toggle="tooltip"], [data-toggle="tooltip"]').each(function() {
                bootstrap.Tooltip.getOrCreateInstance(this);
            });
        }
        modal.handleUpdate();
    };

    var dialog_ref = function(modal, element, body) {
        return {
            modal: modal,
            $modal: $(element),
            $modalBody: $(body),
            close: function() {
                close_dialog(this);
            }
        };
    };

    var show = function(options) {
        var modal_element = document.createElement('div');
        modal_element.className = 'modal fade bootstrap-dialog ' + (options.cssClass || '');
        modal_element.tabIndex = -1;
        modal_element.setAttribute('role', 'dialog');
        modal_element.setAttribute('aria-modal', 'true');

        modal_element.innerHTML = [
            '<div class="modal-dialog modal-dialog-scrollable">',
            '<div class="modal-content">',
            '<div class="modal-header">',
            '<h5 class="modal-title bootstrap-dialog-title"></h5>',
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>',
            '</div>',
            '<div class="modal-body bootstrap-dialog-message"></div>',
            '<div class="modal-footer"></div>',
            '</div>',
            '</div>'
        ].join('');

        var title = modal_element.querySelector('.modal-title');
        var body = modal_element.querySelector('.modal-body');
        var footer = modal_element.querySelector('.modal-footer');

        title.textContent = options.title || '';
        $(body).append(options.message || '');

        document.body.appendChild(modal_element);

        var modal = new bootstrap.Modal(modal_element, {
            backdrop: 'static',
            keyboard: true
        });
        var ref = dialog_ref(modal, modal_element, body);
        var observer = new MutationObserver(function() {
            refresh_content(modal, body);
        });

        observer.observe(body, {
            childList: true,
            subtree: true
        });

        open_dialogs.push(ref);

        $.each(options.buttons || [], function(index, button) {
            var $button = $('<button type="button"></button>')
                .addClass('btn btn-sm ' + normalize_button_class(button.cssClass))
                .attr('id', button.id || '')
                .text(button.label || '');

            $button.on('click', function(event) {
                event.preventDefault();
                if ($.isFunction(button.action)) {
                    button.action(ref);
                }
            });

            if (button.hotkey) {
                $(modal_element).on('keydown.dialog-button-' + button.id, function(event) {
                    if (event.which === button.hotkey) {
                        $button.trigger('click');
                    }
                });
            }

            $(footer).append($button);
        });

        modal_element.addEventListener('hidden.bs.modal', function() {
            open_dialogs = $.grep(open_dialogs, function(dialog) {
                return dialog.$modal[0] !== modal_element;
            });
            observer.disconnect();
            modal.dispose();
            $(modal_element).remove();
        });

        modal.show();
        refresh_content(modal, body);

        return ref;
    };

    var close_all = function() {
        $.each(open_dialogs.slice(), function(index, dialog) {
            dialog.close();
        });
    };

    global.BootstrapDialog = {
        show: show,
        closeAll: close_all
    };
})(window, jQuery, bootstrap);
