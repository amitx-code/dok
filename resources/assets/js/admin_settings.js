const init = () => {

  $('#formUpdateDefault').commonForm('Заполненные данные были успешно сохранены');
  $('#formUpdateDefault1').commonForm('Заполненные данные были успешно сохранены');
  $('#formUpdateDefault2').commonForm('Заполненные данные были успешно сохранены');
  $('#formUpdateDefault3').commonForm('Заполненные данные были успешно сохранены');

  $('#formUpdateProfile').commonForm('Личные данные были успешно сохранены');
  $('#formUpdateAvatar').commonForm('Аватар был успешно сохранён. Перезагрузите страницу чтобы увидеть новый аватар');
  $('#formUpdateAddress').commonForm('Почтовый адрес был успешно сохранён');



$('#formUpdatePassword').commonForm((data, $form) => {
    const $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
    $success.find('span').text('Пароль был успешно изменен');
    $('.alert-area').append($success);
    setTimeout(() => $success.fadeOut(500, () => $success.remove()), 10000);
    $form.find('input[type="password"]').val('');
  });



	$('#formUpdateAfillate').commonForm((data, $form) => {
		if (data.result == 'success') {
			const $alert = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
			$alert.find('span').text('Телефон партнёра был успешно изменен');
			$('.alert-area').append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);
			$('#divUpdateAfillate').slideUp(500);
		} else if (data.result == 'notfound') {
			const $alert = $('<div class="custom-alerts alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
			$alert.find('span').text('Партнёр с таким телефоном отсутствует в базе данных. Пожалуйста проверь, правильно ли был введён номер телефона?');
			$('.alert-area').append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);
		}
	});



}
export { init }