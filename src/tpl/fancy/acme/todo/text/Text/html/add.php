<form action="<?= $this->linkOn('acme\todo\text\Text', 'post', null, 'post')?>" method="post" enctype="multipart/form-data">
	<label for="title">Заголовок</label>
	<input type="text" name="title"/>
	<label for="date">Дата</label>
	<?= $this->render('acme\todo\date\Date', array(action=>'edit', date=>$data->date, name=>'date')); ?>
	<label for="content">Текст</label>
	<textarea name="content"><?= $data->content ?></textarea>
	<input type="hidden" name="id" value="<?= $data->id ?>"/>
	<input type="submit" value="Сохранить" class="button"/>
</form>