<?php

use Config\OSPOS;

?>

        </div>
    </div>

    <div id="footer" class="app-footer">
        <div class="push-spaces footer-surface">
            <strong>
                <?= lang('Common.copyrights', [date('Y')]) ?> ·
                <a href="https://opensourcepos.org" target="_blank"><?= lang('Common.website') ?></a> ·
                <?= esc(config('App')->application_version) ?> -
                <a target="_blank" href="https://github.com/opensourcepos/opensourcepos/commit/<?= esc(config(OSPOS::class)->commit_sha1) ?>">
                    <?= esc(substr(config(OSPOS::class)->commit_sha1, 0, 6)); ?>
                </a>
            </strong>.
        </div>
    </div>
</body>

</html>
