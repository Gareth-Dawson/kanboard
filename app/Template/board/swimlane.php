<tr>
    <?php if (! $hide_swimlane): ?>
        <td width="10%"></td>
    <?php endif ?>

    <?php foreach ($swimlane['columns'] as $column): ?>
    <th>
        <div class="board-add-icon">
            <?= Helper\a('+', 'task', 'create', array('project_id' => $column['project_id'], 'column_id' => $column['id'], 'swimlane_id' => $swimlane['id']), false, 'task-creation-popover', t('Add a new task')) ?>
        </div>

        <?= Helper\escape($column['title']) ?>

        <?php if ($column['task_limit']): ?>
            <span title="<?= t('Task limit') ?>" class="task-limit">
                (<span id="task-number-column-<?= $column['id'] ?>"><?= $column['nb_tasks'] ?></span>/<?= Helper\escape($column['task_limit']) ?>)
            </span>
        <?php else: ?>
            <span title="<?= t('Task count') ?>" class="task-count">
                (<span id="task-number-column-<?= $column['id'] ?>"><?= $column['nb_tasks'] ?></span>)
            </span>
        <?php endif ?>
    </th>
    <?php endforeach ?>
</tr>
<tr>
    <?php if (! $hide_swimlane): ?>
        <th class="board-swimlane-title">
            <?= Helper\escape($swimlane['name']) ?>
        </th>
    <?php endif ?>

    <?php foreach ($swimlane['columns'] as $column): ?>
    <td
        id="column-<?= $column['id'] ?>"
        class="column <?= $column['task_limit'] && count($column['tasks']) > $column['task_limit'] ? 'task-limit-warning' : '' ?>"
        data-column-id="<?= $column['id'] ?>"
        data-swimlane-id="<?= $swimlane['id'] ?>"
        data-task-limit="<?= $column['task_limit'] ?>">

        <?php foreach ($column['tasks'] as $task): ?>
            <?= Helper\template('board/task', array(
                'task' => $task,
                'categories' => $categories,
                'board_highlight_period' => $board_highlight_period,
            )) ?>
        <?php endforeach ?>
    </td>
    <?php endforeach ?>
</tr>