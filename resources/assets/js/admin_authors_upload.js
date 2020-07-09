const init = () => {

  $('#formExamUpload').commonForm((data, $form) => {
    const $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
    $success.find('span').text('Файл был успешно загружен, новый тест-экзамен успешно был создан. Не забудьте обязательно проверить его работоспособность');
    $('.alert-area').append($success);
    setTimeout(() => $success.fadeOut(500, () => $success.remove()), 10000);
    $form.find('input[type="file"]').val('');
    $form.find('input[name="name"]').val('');
    $form.find('input[name="name"]').val('');
  });

}

export { init }