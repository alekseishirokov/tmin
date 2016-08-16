<? if ($data) : ?>
		<? foreach ($data as $item) : ?>
			<div id="content">
				<h3><a href="<?= $this->linkOn('acme\todo\text\Text', 'showItem',  array(id=>$item->id)) ?>"><?= $item->title ?></a></h3>
				<?= $item->content ?>
			</div>
		<? endforeach; ?>
<? endif;