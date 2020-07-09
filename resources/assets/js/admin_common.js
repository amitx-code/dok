const init = () => {

/*
  $('#formAddSitePopup').each(function() {
    const $form = $(this);
    const $popup = $form.closest('.modal');

    $popup.on('shown.bs.modal', e => {
      $form.find('input[type="text"]:eq(0)').focus();
    });

    $form.commonForm(data => {
      document.location.href = '/admin/sites/' + data.id + '/settings';
    })

  });
*/

  $('[data-toggle="tooltip"]').tooltip();

}

export { init }