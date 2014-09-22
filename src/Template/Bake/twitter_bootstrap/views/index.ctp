<?php
use Cake\Utility\Inflector;

echo "<?php\n";
echo "\$this->extend('../Layout/TwitterBootstrap/dashboard');\n";
echo "\$this->start('tb_sidebar');\n";
echo "?>\n";
?>
	<ul class="nav nav-sidebar">
		<li><?= "<?= \$this->Html->link(__('New " . $singularHumanName . "'), ['action' => 'add']) ?>"; ?></li>
		<?php
			$done = [];
			foreach ($associations as $type => $data) {
				foreach ($data as $alias => $details) {
					if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
						echo "\t\t<li><?= \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), ['controller' => '{$details['controller']}', 'action' => 'index']) ?> </li>\n";
						echo "\t\t<li><?= \$this->Html->link(__('New " . Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) . "'), ['controller' => '{$details['controller']}', 'action' => 'add']) ?> </li>\n";
						$done[] = $details['controller'];
					}
				}
			}
		?>
	</ul>
<?php
echo "<?= \$this->end() ?>";

$fields = collection($fields)
	->filter(function($field) use ($schema) {
		return !in_array($schema->columnType($field), ['binary', 'text']);
	})
	->take(7);
?>

<table class="table" cellpadding="0" cellspacing="0">
<thead>
	<tr>
<?php foreach ($fields as $field): ?>
	<th><?= "<?= \$this->Paginator->sort('{$field}') ?>"; ?></th>
<?php endforeach; ?>
	<th class="actions"><?= "<?= __('Actions') ?>"; ?></th>
	</tr>
</thead>
<tbody>
<?php
echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
echo "\t\t<tr>\n";
	foreach ($fields as $field) {
		$isKey = false;
		if (!empty($associations['BelongsTo'])) {
			foreach ($associations['BelongsTo'] as $alias => $details) {
				if ($field === $details['foreignKey']) {
					$isKey = true;
					echo "\t\t\t<td>\n\t\t\t\t<?= \${$singularVar}->has('{$details['property']}') ? \$this->Html->link(\${$singularVar}->{$details['property']}->{$details['displayField']}, ['controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}->{$details['property']}->{$details['primaryKey'][0]}]) : '' ?>\n\t\t\t</td>\n";
					break;
				}
			}
		}
		if ($isKey !== true) {
			if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
				echo "\t\t\t<td><?= h(\${$singularVar}->{$field}) ?></td>\n";
			} else {
				echo "\t\t\t<td><?= \$this->Number->format(\${$singularVar}->{$field}) ?></td>\n";
			}
		}
	}

	$pk = "\${$singularVar}->{$primaryKey[0]}";

	echo "\t\t\t<td class=\"actions\">\n";
	echo "\t\t\t\t<?= \$this->Html->link(__('View'), ['action' => 'view', {$pk}]) ?>\n";
	echo "\t\t\t\t<?= \$this->Html->link(__('Edit'), ['action' => 'edit', {$pk}]) ?>\n";
	echo "\t\t\t\t<?= \$this->Form->postLink(__('Delete'), ['action' => 'delete', {$pk}], ['confirm' => __('Are you sure you want to delete # {0}?', {$pk})]) ?>\n";
	echo "\t\t\t</td>\n";
echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
</tbody>
</table>
<div class="paginator">
	<ul class="pagination">
	<?php
		echo "<?php\n";
		echo "\t\t\techo \$this->Paginator->prev('< ' . __('previous'));\n";
		echo "\t\t\techo \$this->Paginator->numbers();\n";
		echo "\t\t\techo \$this->Paginator->next(__('next') . ' >');\n";
		echo "\t\t?>\n";
	?>
	</ul>
	<p><?= "<?= \$this->Paginator->counter() ?>"; ?></p>
</div>
