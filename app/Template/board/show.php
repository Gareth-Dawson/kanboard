<table id="board"
       data-project-id="<?= $project['id'] ?>"
       data-check-interval="<?= $board_private_refresh_interval ?>"
       data-save-url="<?= Helper\u('board', 'save', array('project_id' => $project['id'])) ?>"
       data-check-url="<?= Helper\u('board', 'check', array('project_id' => $project['id'], 'timestamp' => time())) ?>"
>
<?php foreach ($swimlanes as $swimlane): ?>
    <?php if (empty($swimlane['columns'])): ?>
        <p class="alert alert-error"><?= t('There is no column in your project!') ?></p>
    <?php else: ?>
        <?= Helper\template('board/swimlane', array(
            'swimlane' => $swimlane,
            'board_highlight_period' => $board_highlight_period,
            'categories' => $categories,
            'hide_swimlane' => count($swimlanes) === 1,
        )) ?>
    <?php endif ?>
<?php endforeach ?>
</table>
