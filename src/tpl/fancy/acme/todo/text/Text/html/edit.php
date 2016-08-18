<h1>ToDo</h1>
<div class="actions">
	<a href="<?= $this->linkOn('acme\todo\text\Text', 'add') ?>">Add</a>
</div>
<? if ($data) : ?>
		<? foreach ($data as $item) : ?>
			<div id="content">
				<h3><a href="<?= $this->linkOn('acme\todo\text\Text', 'editItem',  array(id=>$item->id)) ?>"><?= $item->title ?></a>
				(<?= $this->render('acme\todo\date\Date', array(action=>'show', date=>$item->date)); ?>)</h3>
				<a href="<?= $this->linkOn('acme\todo\text\Text', 'delete', array(id=>$item->id), 'delete') ?>">Удалить</a>
				<?= $item->content ?>
			</div>
		<? endforeach; ?>
<? endif;