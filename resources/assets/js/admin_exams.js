import { sendPost } from './admin_req';

const start = (options) => {
  $('#buttonStart').click(function() {
    console.log('caoasooo');
    sendPost(`/admin/exam/${ options.test_id }/start`, {})
      .then(data => {
        document.location.href = `/admin/exam/${options.test_id}/${data.session}`;
      })
      .catch(e => {
        alert(e.responseJSON.reason);
      });


    return false;
  });
}

const test = (options) => {
  console.log('test-initted');

  const $counter = $('.question-counter');
  const currentTimer = $counter.text() * 1;
  const endTime = currentTimer * 1000 + (new Date()).getTime();

  const submitAnswer = () => {
    const answers = $form.find('input:checked').map((i, item) => item.value).toArray();
    // if (!answers.length) return false;

    sendPost($form.prop('action'), {answer: answers, crash: true})
      .then(data => {
        document.location.reload();
      })
      .catch(e => {
        console.log(e.responseJSON);
      });


    return false;
  }

  const timer = setInterval(() => {
    const currentTime = (new Date()).getTime();
    const timeLeft = Math.max(0, Math.round((endTime - currentTime) / 1000));
    if (timeLeft) {
      $counter.text(timeLeft);

    } else {
      clearInterval(timer);
      submitAnswer();
    }

  }, 600);


  const $form = $('#formAnswer');
  $form.submit(submitAnswer);



  $('#buttonStart').click(function() {
    sendPost(`/admin/exam/${ options.test_id }/start`, {})
      .then(data => {
        document.location.href = `/admin/exam/${options.test_id}/${data.session}`;
      })
      .catch(e => {
        alert(e.responseJSON.reason);
      });


    return false;
  });

}


const results = (options) => {
  const $table = $('#tableTestResults');

  $table.on({
    click: e => {
      const $el = $(e.target);
      if ($el.is('.btn-group') || $el.closest('.btn-group').length) return;

      document.location.href = '/admin/results/' + $(e.currentTarget).attr('data-item-id');
    }

  }, 'tr[data-item-id]');

}

export {
  start,
  test,
  results
}