

const sendGet = (url, params) => new Promise((resolve, reject) => {
  console.log('reqie');
  $.ajax({
    type: 'GET',
    dataType: 'json',
    url: url,
    data: params,
    success: data => resolve(data),
    // error: reject(e.responseJSON, e.status, e),
    error: e => reject(e),
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
})


const sendPost = (url, params) => new Promise((resolve, reject) => {
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: url,
    data: params,
    success: data => resolve(data),
    error: e => reject(e.responseJSON, e.status),
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
})

const init = () => {

  $.fn.commonForm = function(success, error, options) {
    return this.each(function () {
      const $form = $(this);

      const beforeSubmit = () => {
        $form.find('.error').addClass('hidden');
      }

      const successHandler = typeof success === 'function' ? data => success(data, $form) : (data) => {
        if (typeof success === 'string') {
          const $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
          $success.find('span').text(success);
          let $alertArea = $('.alert-area');

          if ($alertArea.length > 1) {
            console.log('isolatin');
            let $parent = $form;
            while ($parent.prop('tagName').toLowerCase() !== 'body') {
              $alertArea = $parent.find('.alert-area');
              if ($alertArea.length) {
                $alertArea = $alertArea.eq(0);
                break;
              }

              $parent = $parent.parent();
            }
          }

          if ($alertArea.length) {
            $alertArea.append($success);
            setTimeout(() => $success.fadeOut(500, () => $success.remove()), 10000);
          }
        }
      }

      const errorHandler = typeof error === 'function' ? (e) => error(e.responseJSON, e.status) : (e) => {
        const data = e.responseJSON;
    
        if (data && typeof data.redirect != 'undefined') {
            document.location.href = data.redirect;
            return;
        }


        if (data && typeof data.errors !== 'undefined') {
          let firstErrorField = false;
          

          Object.keys(data.errors).map(key => {
            const $field = $form.find('[name="' + key + '"]');
            const $group = $field.closest('.form-group,.quasi-form-group');
            let $error = $group.find('.error');
            if ($error.length === 0) {
              $error = $('<label class="help-inline help-small no-left-padding hidden error"></label>');

              if ($group.is('.quasi-form-group')) {
                $group.append($error);

              } else {
                $group.find('div:last').append($error);
              }
            }

            console.log('ero: ' + $error.length + ' - ' + data.errors[key]);

            $error.removeClass('hidden').html(data.errors[key]);
            if (firstErrorField === false) firstErrorField = $field;
          });

          if (firstErrorField) {
            firstErrorField.focus();
          }
        }
      };

      $form.ajaxForm({
        beforeSubmit,
        dataType: 'json',
        success: successHandler,
        error: errorHandler
      });
    });
  }
}

export {
  sendGet,
  sendPost,
  init
}