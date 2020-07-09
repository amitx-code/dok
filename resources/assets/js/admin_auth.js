require('./admin_req').init();

const init = () => {
  const $page = $('.page-auth');
  if (!$page.length) return;

  const $formLogin = $('#formLogin');
  const $formRegister = $('#formRegister');
  const $formRemind = $('#formRemindPassword');


$('.button-register').click(e => {
	$('.alert').slideUp(300);
	$('#reminded').html('');
	$formRemind.hide();
	$formLogin.hide();
	$formRegister.show();
	$formRegister.find('input[name="email"]').focus();
	setLocation("/reg");
	return false;
});

$page.find('.button-login').click(e => {
	$('.alert').slideUp(300);
	$('#reminded').html('');
	$formRemind.hide();
	$formRegister.hide();
	$formLogin.show();
	$formLogin.find('input[name="email"]').focus();
	setLocation("/login");
	return false;
});

$page.find('.button-forget').click(e => {
	$('.alert').slideUp(300);
	$('#reminded').html('');
	$formLogin.hide();
	$formRegister.hide();
	$formRemind.show();
	setLocation("/reminder");
	return false;
});


$formLogin.find('input[name="email"]').focus();


	$formLogin.commonForm(data => {
		//console.log(data);
		if (data.url != "") {
			document.location.href = data.url;
		} else {
	 		document.location.href = '/admin';
		}
	})


	$formRegister.commonForm(data => {
		console.log(data);
		if (data.url != "") {
			document.location.href = data.url;
		} else {
			//document.location.href = '/admin/settings/personal/new';
	 		document.location.href = '/admin';
		}
	}) 


	$formRemind.commonForm(data => {
		//console.log(data);
		if (data.result == 'notfound') {
			$('#reminderError').html('Такой E-mail не зарегистрирован').removeClass('hidden');
			}
		else {
			if (data.url != "") {
				document.location.href = data.url;
			} else {
			    	document.location.href = '/admin/login?reminded';
			}
		}
	})


  /*
  $('#buttonLogout').click(e => {
  });
  */
}

export {
  init
}


function setLocation(curLoc){
    try {
      history.pushState(null, null, curLoc);
      return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}