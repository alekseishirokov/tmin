<h1>ToDo</h1>
<div class="actions">
	<a href="<?= $this->linkOn('acme\todo\text\Text', 'edit') ?>">редактировать</a>
</div>
<? if ($data) : ?>
		<? foreach ($data as $item) : ?>
			<div id="content">
				<h3><a href="<?= $this->linkOn('acme\todo\text\Text', 'showItem',  array(id=>$item->id)) ?>"><?= $item->title ?></a>
					(<?= $this->render('acme\todo\date\Date', array(action=>'show', date=>$item->date)); ?>) </h3>
				<?= $item->content ?>
			</div>
		<? endforeach; ?>
<? endif;