<? if ($data) : ?>
	<div id="content">
		<h3><?= $data->title ?> (<?= $this->render('acme\todo\date\Date', array(action=>'show', date=>$data->date, name=>'date')); ?>)</h3>
		<?= $data->content ?>
	</div>
<? endif;