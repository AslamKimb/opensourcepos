(function(dialog_support, $) {

    var btn_id, dialog_ref;

    var hide = function() {
        dialog_ref && dialog_ref.close();
    };

    var clicked_id = function() {
        return btn_id;
    };

    var submit = function(button_id) {
        return function(dlog_ref) {
            const form = $('form', dlog_ref.$modalBody).first();
            const validator = form.data('validator');
            const submitted = validator && validator.formSubmitted;

            btn_id = button_id;
            dialog_ref = dlog_ref;

            if (button_id == 'submit' && (!submitted && btn_id != "btnNew")) {
                form.submit();
                validator.valid() && $('#submit').prop('disabled', true).css('opacity', 0.5);
            }
            return false;
        }
    };

    var button_class = {
        'submit' : 'btn-primary',
        'delete' : 'btn-danger'
    };

    var init = function(selector) {

        var buttons = function(event) {
            var buttons = [];
            var dialog_class = 'modal-dlg';
            $.each($(this).attr('class').split(/\s+/), function(classIndex, className) {
                var width_class = className.split("modal-dlg-");
                if (width_class && width_class.length > 1) {
                    dialog_class = className;
                }
            });

            var has_new_btn = "btnNew" in $(this).data();
            $.each($(this).data(), function(name, value) {
                var btn_class = name.split("btn");
                if (btn_class && btn_class.length > 1) {
                    var btn_name = btn_class[1].toLowerCase();
                    var is_submit = btn_name == 'submit';
                    var is_new = btn_name === 'new';
                    var is_enter = has_new_btn ? is_new: is_submit;
                    buttons.push({
                        id: btn_name,
                        label: value,
                        cssClass: button_class[btn_name],
                        hotkey: is_enter ? 13 : undefined, // Enter
                        action: submit(btn_name)
                    });
                }
            });

            !buttons.length && buttons.push({
                id: 'close',
                label: lang.line('common_close'),
                cssClass: 'btn-primary',
                action: function(dialog_ref) {
                    dialog_ref.close();
                }
            });
            return { buttons: buttons.sort(function(a, b) {
                return ($(b).text()) < ($(a).text()) ? -1 : 1;
            }), cssClass: dialog_class};
        };

        $(selector).each(function(index, $element) {

            return $(selector).off('click').on('click', function(event) {
                var $link = $(event.target);
                $link = !$link.is("a, button") ? $link.parents("a, button") : $link ;
                BootstrapDialog.show($.extend({
                    title: $link.attr('title'),
                    message: (function() {
                        var node = $('<div></div>');
                        $.get($link.attr('href') || $link.data('href'), function(data) {
                            node.html(data);
                        });
                        return node;
                    })
                }, buttons.call(this, event)));

                return false;
            });
        });
    };

    $.extend(dialog_support, {
        init: init,
        submit: submit,
        hide: hide,
        clicked_id: clicked_id
    });

})(window.dialog_support = window.dialog_support || {}, jQuery);

(function(table_support, $) {

    var enable_actions = function(callback) {
        return function() {
            var selection_empty = selected_rows().length == 0;
            $("#toolbar button:not(.dropdown-toggle)").attr('disabled', selection_empty);
            typeof callback == 'function' && callback();
        }
    };

    var table = function() {
        return $("#table").data('bootstrap.table');
    }

    var selected_ids = function () {
        return $.map(table().getSelections(), function (element) {
            return element[options.uniqueId || 'id'] !== '-' ? element[options.uniqueId || 'id'] : null;
        });
    };

    var selected_rows = function () {
        return $("#table td input:checkbox:checked").parents("tr");
    };

    var add_role_class = function(column, role) {
        var class_name = "ospos-mobile-" + role;
        var classes = (column.class || "").split(/\s+/);

        if ($.inArray(class_name, classes) === -1) {
            classes.push(class_name);
        }

        column.class = $.trim(classes.join(" "));
        return column;
    };

    var normalize_columns = function(columns) {
        var text_count = 0;
        var metric_count = 0;
        var action_fields = {
            edit: true,
            email: true,
            messages: true,
            inventory: true,
            stock: true,
            invoice: true,
            receipt: true
        };

        return $.map(columns || [], function(column) {
            column = $.extend({}, column);
            var field = (column.field || "").toString();
            var title = $("<div>").html(column.title || "").text().replace(/\s+/g, " ").trim();
            var field_key = field.toLowerCase();
            var is_checkbox = column.checkbox === true;
            var is_action = !is_checkbox && (action_fields[field_key] || !title.length);
            var is_identifier = !is_checkbox && !is_action && /(^id$|[._]id$|_id$)/.test(field_key);
            var is_metric = !is_checkbox && !is_action && /(amount|balance|cost|price|quantity|qty|total|due|paid|profit|tax|discount|percent|rate|value)/.test(field_key);
            var role = column.mobileRole || "detail";
            var order = column.mobileOrder;

            if (!column.mobileRole) {
                if (is_checkbox) {
                    role = "selector";
                    order = 0;
                } else if (is_action) {
                    role = "action";
                    order = 90;
                } else if (is_identifier) {
                    role = "identifier";
                    order = 80;
                } else if (is_metric) {
                    role = "metric";
                    order = 40 + metric_count++;
                } else if (title.length) {
                    role = text_count === 0 ? "primary" : "secondary";
                    order = text_count === 0 ? 10 : 20 + text_count;
                    text_count++;
                }
            }

            column.mobileRole = role;
            column.mobileOrder = order !== undefined ? order : 60;
            column.cardVisible = column.cardVisible !== undefined ? column.cardVisible : role !== "identifier";
            return add_role_class(column, role);
        });
    };

    var get_normalized_columns = function() {
        var instance = table();
        var columns = instance && instance.options && instance.options.columns ? instance.options.columns : options.headers;

        if ($.isArray(columns) && $.isArray(columns[0])) {
            columns = columns[0];
        }

        return columns || [];
    };

    var apply_mobile_card_layout = function() {
        var columns = get_normalized_columns();

        $("#table").closest(".fixed-table-container").find("tbody tr").each(function() {
            var $row = $(this);
            var $cards = $row.find(".card-view");

            if (!$cards.length) {
                return;
            }

            $row.addClass("ospos-card-row");
            $cards.each(function(index) {
                var $card = $(this);
                var column = columns[index] || {};
                var role = column.mobileRole || "detail";

                $card
                    .addClass("ospos-card-item")
                    .addClass("ospos-card-" + role)
                    .css("order", column.mobileOrder || 60);

                if (column.cardVisible === false) {
                    $card.addClass("ospos-card-hidden");
                }

                if (role === "action" || role === "selector") {
                    $card.find(".title").attr("aria-hidden", "true");
                }
            });

            var $actions = $row.find(".ospos-card-action");
            if ($actions.length && !$actions.parent().hasClass("ospos-card-actions")) {
                $actions.wrapAll('<div class="ospos-card-actions"></div>');
            }
        });
    };

    var mark_scroll_containers = function() {
        $("#table_holder, .register-table-container, .report-table-container").each(function() {
            var is_scrollable = this.scrollWidth > this.clientWidth + 1;
            $(this).toggleClass("is-scrollable", is_scrollable);
        });
    };

    var row_selector = function(id) {
        return "tr[data-uniqueid='" + id + "']";
    };

    var rows_selector = function(ids) {
        var selectors = [];
        ids = ids instanceof Array ? ids : ("" + ids).split(":");
        $.each(ids, function(index, element) {
            selectors.push(row_selector(element));
        });
        return selectors;;
    };

    var highlight_row = function (id, color) {
        $(rows_selector(id)).each(function(index, element) {
            var original = $(element).css('backgroundColor');
            $(element).find("td").animate({backgroundColor: color || '#e1ffdd'}, "slow", "linear")
                .animate({backgroundColor: color || '#e1ffdd'}, 5000)
                .animate({backgroundColor: original}, "slow", "linear");
        });
    };

    var do_action = function(action) {
        return function (url, ids) {
            if (confirm($.fn.bootstrapTable.defaults.formatConfirmAction(action))) {
                $.post((url || options.resource) + '/' + action, {'ids[]': ids || selected_ids()}, function (response) {
                    // Delete was successful, remove checkbox rows
                    if (response.success) {
                        var selector = ids ? row_selector(ids) : selected_rows();
                        table().collapseAllRows();
                        $(selector).each(function (index, element) {
                            $(this).find("td").animate({backgroundColor: "green"}, 1200, "linear")
                                .end().animate({opacity: 0}, 1200, "linear", function () {
                                table().remove({
                                    field: options.uniqueId,
                                    values: selected_ids()
                                });
                                if (index == $(selector).length - 1) {
                                    refresh();
                                    enable_actions();
                                }
                            });
                        });
                        $.notify(response.message, {type: 'success'});
                    } else {
                        $.notify(response.message, {type: 'danger'});
                    }
                }, "json");
            } else {
                return false;
            }
        };
    };

    var load_success = function(callback) {
        return function(response) {
            typeof options.load_callback == 'function' && options.load_callback();
            options.load_callback = undefined;
            dialog_support.init("a.modal-dlg");
            typeof callback == 'function' && callback.call(this, response);
        }
    };

    var options;

    var toggle_column_visibility = function() {
        if (localStorage[options.employee_id]) {
            var user_settings = JSON.parse(localStorage[options.employee_id]);
            user_settings[options.resource] && $.each(user_settings[options.resource], function(index, element) {
                element ? table().showColumn(index) : table().hideColumn(index);
            });
        }
    };

    var init = function (_options) {
        options = _options;
        options.headers = normalize_columns(options.headers);
        enable_actions = enable_actions(options.enableActions);
        load_success = load_success(options.onLoadSuccess);
        var post_body = options.onPostBody;
        const export_suffix = new Date().toISOString().slice(0, 16).replace(/(-|\s*|T|:)*/g,"");
        $('#table')
            .addClass("table")
            .addClass("table-striped")
            .addClass("table-bordered")
            .addClass("table-hover")
            .bootstrapTable($.extend(options, {
            columns: options.headers,
            stickyHeader: true,
            url: options.resource + '/search',
            sidePagination: 'server',
            selectItemName: 'btSelectItem',
            pageSize: options.pageSize,
            pagination: true,
            search: options.resource || false,
            showColumns: true,
            clickToSelect: true,
            mobileResponsive: true,
            checkOnInit: true,
            minWidth: 768,
            showExport: true,
            exportDataType: 'basic',
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
            exportOptions: {
                fileName: options.resource.replace(/.*\/(.*?)$/g, '$1') + "_" + export_suffix
            },
            onPageChange: function(response) {
                load_success(response);
                enable_actions();
            },
            toolbar: '#toolbar',
            uniqueId: options.uniqueId || 'id',
            trimOnSearch: false,
            onCheck: enable_actions,
            onUncheck: enable_actions,
            onCheckAll: enable_actions,
            onUncheckAll: enable_actions,
            onLoadSuccess: function(response) {
                load_success(response);
                enable_actions();
                apply_mobile_card_layout();
                mark_scroll_containers();
            },
            onPostBody: function(data) {
                apply_mobile_card_layout();
                mark_scroll_containers();
                typeof post_body == 'function' && post_body.call(this, data);
            },
            onColumnSwitch : function(field, checked) {
                var user_settings = localStorage[options.employee_id];
                user_settings = (user_settings && JSON.parse(user_settings)) || {};
                user_settings[options.resource] = user_settings[options.resource] || {};
                user_settings[options.resource][field] = checked;
                localStorage[options.employee_id] = JSON.stringify(user_settings);
                dialog_support.init("a.modal-dlg");
            },
            queryParamsType: 'limit',
            iconSize: 'sm',
            iconsPrefix: 'bi',
            icons: {
                paginationSwitchDown: 'bi-caret-down-square',
                paginationSwitchUp: 'bi-caret-up-square',
                refresh: 'bi-arrow-clockwise',
                toggleOff: 'bi-toggle-off',
                toggleOn: 'bi-toggle-on',
                columns: 'bi-list-check',
                export: 'bi-download',
                detailOpen: 'bi-plus',
                detailClose: 'bi-dash'
            },
            silentSort: true,
            paginationVAlign: 'bottom',
            escape: true
        }));
        enable_actions();
        init_delete();
        init_restore();
        toggle_column_visibility();
        dialog_support.init("button.modal-dlg");
        $(window).off("resize.osposTableSupport").on("resize.osposTableSupport", mark_scroll_containers);
    };

    var init_delete = function (confirmMessage) {
        $("#delete").click(function(event) {
            do_action("delete")();
        });
    };

    var init_restore = function (confirmMessage) {
        $("#restore").click(function(event) {
            do_action("restore")();
        });
    };

    var refresh = function() {
        table().refresh();
    }

    var submit_handler = function(url) {
        return function (resource, response) {
            var id = response.id !== undefined ? response.id.toString() : "";
            if (!response.success) {
                $.notify(response.message, { type: 'danger' });
            } else {
                var message = response.message;
                var selector = rows_selector(response.id);
                var rows = $(selector.join(",")).length;
                if (rows > 0 && rows < 15) {
                    var ids = id.split(":");
                    $.get([url || resource + '/row', id].join("/"), {}, function (response) {
                        $.each(selector, function (index, element) {
                            var id = $(element).data('uniqueid');
                            table().updateByUniqueId({id: id, row: response[id] || response});
                        });
                        dialog_support.init("a.modal-dlg");
                        highlight_row(ids);
                    }, 'json');
                } else {
                    // Call hightlight function once after refresh
                    options.load_callback = function () {
                        enable_actions();
                        highlight_row(id);
                    };
                    refresh();
                }
                $.notify(message, {type: 'success' });
            }
            return false;
        };
    };

    var handle_submit = submit_handler();

    $.extend(table_support, {
        submit_handler: function(url) {
            this.handle_submit = submit_handler(url);
        },
        handle_submit: handle_submit,
        init: init,
        do_delete: do_action("delete"),
        do_restore: do_action("restore"),
        refresh : refresh,
        selected_ids : selected_ids,
    });

    $(window).on("load resize.osposScrollContainers", mark_scroll_containers);

})(window.table_support = window.table_support || {}, jQuery);

(function(form_support, $) {

    form_support.error = {
        errorClass: "has-error",
        errorLabelContainer: "#error_message_box",
        wrapper: "li",
        highlight: function (e) {
            $(e).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (e) {
            $(e).closest('.form-group').removeClass('has-error');
        }
    };

    form_support.handler = $.extend({

        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response)
                {
                    $.notify(response.message, { type: response.success ? 'success' : 'danger' });
                },
                dataType: 'json'
            });
        },

        rules:
        {

        },

        messages:
        {

        }
    }, form_support.error);

})(window.form_support = window.form_support || {}, jQuery);

function number_sorter(a, b) {
    a = +a.replace(/[^\-0-9]+/g, '');
    b = +b.replace(/[^\-0-9]+/g, '');
    return a - b;
}
