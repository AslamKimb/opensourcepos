<?php

use Config\OSPOS;

$brand_name = brand_display_name($config);
?>

                </div>
            </div>

                <div id="footer" class="app-footer">
                    <div class="jumbotron push-spaces footer-surface">
                        <strong>
                            <?= lang('Common.copyrights', [date('Y')]) ?> ·
                            <?= esc($brand_name) ?>
                            <?php if (brand_show_powered_by($config)): ?>
                                · <?= lang('Common.powered_by') ?>
                                <a href="https://opensourcepos.org" target="_blank">OSPOS</a>
                                <?= esc(config('App')->application_version) ?> -
                                <a target="_blank" href="https://github.com/opensourcepos/opensourcepos/commit/<?= esc(config(OSPOS::class)->commit_sha1) ?>">
                                    <?= esc(substr(config(OSPOS::class)->commit_sha1, 0, 6)); ?>
                                </a>
                            <?php endif; ?>
                        </strong>.
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
