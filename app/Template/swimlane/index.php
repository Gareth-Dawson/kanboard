<?php if (! empty($swimlanes)): ?>
<div class="page-header">
    <h2><?= t('Swimlanes') ?></h2>
</div>
<table>
    <tr>
        <th><?= t('Name') ?></th>
        <th><?= t('Actions') ?></th>
    </tr>
    <?php foreach ($swimlanes as $swimlane_id => $swimlane_name): ?>
    <tr>
        <td><?= Helper\escape($swimlane_name) ?></td>
        <td>
            <ul>
                <li>
                    <?= Helper\a(t('Edit'), 'swimlane', 'edit', array('project_id' => $project['id'], 'swimlane_id' => $swimlane_id)) ?>
                </li>
                <li>
                    <?= Helper\a(t('Disable'), 'swimlane', 'disable', array('project_id' => $project['id'], 'swimlane_id' => $swimlane_id)) ?>
                </li>
                <li>
                    <?= Helper\a(t('Remove'), 'swimlane', 'confirm', array('project_id' => $project['id'], 'swimlane_id' => $swimlane_id)) ?>
                </li>
            </ul>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<?php endif ?>

<div class="page-header">
    <h2><?= t('Add a new swimlane') ?></h2>
</div>
<form method="post" action="<?= Helper\u('swimlane', 'save', array('project_id' => $project['id'])) ?>" autocomplete="off">

    <?= Helper\form_csrf() ?>
    <?= Helper\form_hidden('project_id', $values) ?>

    <?= Helper\form_label(t('Name'), 'name') ?>
    <?= Helper\form_text('name', $values, $errors, array('autofocus required')) ?>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</form>