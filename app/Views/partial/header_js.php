<?php
/**
 * @var array $config
 */
?>

<script type="text/javascript">
    // Live clock
    var clock_tick = function clock_tick() {
        setInterval('update_clock();', 1000);
    }

    // Start the clock immediately
    clock_tick();

    var update_clock = function update_clock() {
        document.getElementById('liveclock').innerHTML = moment().format("<?= dateformat_momentjs($config['dateformat'] . ' ' . $config['timeformat']) ?>");
    }

    const notify = $.notify;

    $.notify = function(content, options) {
        const message = typeof content === "object" ? content.message : content;
        const sanitizedMessage = DOMPurify.sanitize(message);
        return notify(sanitizedMessage, options);
    };

    $.notifyDefaults({
        placement: {
            align: "<?= esc($config['notify_horizontal_position'], 'js') ?>",
            from: "<?= esc($config['notify_vertical_position'], 'js') ?>"
        }
    });

    var csrf_token = function() {
        return "<?= csrf_hash() ?>";
    };

    var csrf_form_base = function() {
        return {
            <?= esc(config('Security')->tokenName, 'js') ?>: function() {
                return csrf_token()
            }
        }
    };

    var setup_csrf_token = function() {
        $('input[name="<?= esc(config('Security')->tokenName, 'js') ?>"]').val(csrf_token());
    };

    var ajax = $.ajax;

    $.ajax = function() {
        var args = arguments[0];
        if (args['type'] && args['type'].toLowerCase() == 'post' && csrf_token()) {
            if (typeof args['data'] === 'string') {
                args['data'] += '&' + $.param(csrf_form_base());
            } else {
                args['data'] = $.extend(args['data'], csrf_form_base());
            }
        }

        return ajax.apply(this, arguments);
    };

    $(document).ajaxComplete(setup_csrf_token);
    $(document).ready(function() {
        const shellStorageKey = 'ospos.shell.collapsed';
        const shellBreakpoint = getComputedStyle(document.documentElement).getPropertyValue('--ui-breakpoint-shell-collapse').trim() || '991px';
        const shellMedia = window.matchMedia(`(max-width: ${shellBreakpoint})`);
        const $shell = $('.app-shell');
        const $shellToggle = $('.app-shell-toggle');
        const $shellBackdrop = $('.app-shell-backdrop');
        const $shellNavLinks = $('#app-navigation a');
        let shellCollapsed = false;
        let shellNavOpen = false;

        try {
            shellCollapsed = window.localStorage.getItem(shellStorageKey) === '1';
        } catch (error) {
            shellCollapsed = false;
        }

        const syncShellState = function() {
            const isOverlayMode = shellMedia.matches;
            $shell.toggleClass('app-shell-collapsed', !isOverlayMode && shellCollapsed);
            $shell.toggleClass('app-shell-nav-open', isOverlayMode && shellNavOpen);
            $('body').toggleClass('app-shell-nav-locked', isOverlayMode && shellNavOpen);

            const ariaExpanded = isOverlayMode ? shellNavOpen : !shellCollapsed;
            $shellToggle.attr('aria-expanded', ariaExpanded ? 'true' : 'false');
        };

        const closeShellOverlay = function() {
            shellNavOpen = false;
            syncShellState();
        };

        $shellToggle.on('click', function(event) {
            event.preventDefault();

            if (shellMedia.matches) {
                shellNavOpen = !shellNavOpen;
            } else {
                shellCollapsed = !shellCollapsed;
                try {
                    window.localStorage.setItem(shellStorageKey, shellCollapsed ? '1' : '0');
                } catch (error) {
                    // Ignore storage errors and keep the in-memory state.
                }
            }

            syncShellState();
        });

        $shellBackdrop.on('click', closeShellOverlay);
        $shellNavLinks.on('click', function() {
            if (shellMedia.matches) {
                closeShellOverlay();
            }
        });

        $(document).on('keyup', function(event) {
            if (event.key === 'Escape' && shellMedia.matches && shellNavOpen) {
                closeShellOverlay();
            }
        });

        const handleShellViewportChange = function() {
            if (!shellMedia.matches) {
                shellNavOpen = false;
            }

            syncShellState();
        };

        if (typeof shellMedia.addEventListener === 'function') {
            shellMedia.addEventListener('change', handleShellViewportChange);
        } else if (typeof shellMedia.addListener === 'function') {
            shellMedia.addListener(handleShellViewportChange);
        }

        handleShellViewportChange();

        $("#logout").click(function(event) {
            event.preventDefault();
            $.ajax({
                url: "<?= site_url('home/logout'); ?>",
                data: {
                    "<?= esc(config('Security')->tokenName, 'js'); ?>": csrf_token()
                },
                success: function() {
                    window.location.href = '<?= site_url(); ?>';
                },
                method: "POST"
            });
        });
    });

    var submit = $.fn.submit;

    $.fn.submit = function() {
        setup_csrf_token();
        submit.apply(this, arguments);
    };
</script>
