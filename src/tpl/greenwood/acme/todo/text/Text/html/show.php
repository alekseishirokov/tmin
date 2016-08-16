<? if ($data) : ?>
		<? foreach ($data as $item) : ?>
			<div id="content" style="width:50%; height:200px; float:left;">
				<h3><?= $item->title ?></h3>
				<?= $item->content ?>
			</div>
		<? endforeach; ?>
<? endif;