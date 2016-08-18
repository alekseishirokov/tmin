<form action="<?= $this->linkOn('acme\todo\text\Text', 'put', array(id=>$data->id), 'post')?>" method="post" enctype="multipart/form-data" id="n-form">
	<label for="title">Заголовок</label>
	<input type="text" name="title" id="title" value="<?= $data->title ?>"/>
	<label for="date">Дата</label>
	<?= $this->render('acme\todo\date\Date', array(action=>'edit', date=>$data->date, name=>'date')); ?>
	<label for="content">Текст</label>
	<textarea name="content" id="content"><?= $data->content ?></textarea>
	<input type="hidden" name="id" id="id" value="<?= $data->id ?>"/>
	<input type="submit" value="Сохранить" class="button"/>
</form>