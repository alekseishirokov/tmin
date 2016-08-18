<div class="form-field">
<input type="text" name="<?= $data->name ?>" id="<?= $data->name ?>" value="<?= $data->date ?>" style="display:none;"/>
<input type="text" name="<?= $data->name ?>-edit" id="<?= $data->name ?>-edit" value="<?= $data->dateEdit ?>"/>
</div>
<script>
	jQuery('#<?= $data->name ?>-edit').datepicker({
		altField:'#<?= $data->name ?>',
		altFormat:'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		buttonImage: '/img/acme/todo/date/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd.mm.yy',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		dayNamesShort: ['Вос', 'Пон', 'Втр', 'Сре', 'Чет', 'Пят', 'Суб'],
		firstDay: 1,
		gotoCurrent: true,
		numberOfMonths: 1,
		closeText: 'X',
		currentText: 'Сегодня',
		showButtonPanel: false,
		showOn: 'both'
	});
</script>
<? $this->js('/js/jquery-1.6.2.min.js'); ?>
<? $this->js('/js/jquery-ui-1.8.16.custom.min.js'); ?>
<? $this->css('/css/smoothness/jquery-ui-1.8.16.custom.css');