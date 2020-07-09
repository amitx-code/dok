<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js"> <!--<![endif]--><head>
    <meta charset="utf-8"/>
    <title>Вход</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <link rel="shortcut icon" href="/favicon.ico"/>

    <link href="/css/admin_bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_jquery.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_fonts.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_app.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_login.css" rel="stylesheet" type="text/css"/>


</head>
<body class="login" style="
@if(Config('global.logo_login.color_bg'))
background-color: {{ Config('global.logo_login.color_bg') }};
@endif
@if(Config('global.logo_login.img_bg'))
background-image: url('{{ Config('global.logo_login.img_bg') }}'); background-position: center center; background-repeat: no-repeat;
@endif
"
>


<br><br>

@if(Config('global.logo_login.img'))
	<div class="logo">
		<img src="{{ Config('global.logo_login.img') }}" alt="" style="
		@if(Config('global.logo_login.width')) width: {{ Config('global.logo_login.width') }}; @endif
		@if(Config('global.logo_login.height')) height: {{ Config('global.logo_login.height') }}; @endif
		">
	</div>
@endif














<div class="content page-auth"  style="width:auto; max-width:550px;">






{{--
<h2 class="text-center"><b>Внимание! Идёт перепрограммирование авторизации в системе - возможны ошибки, или может иногда не пускать внутрь по рабочим паролям - просьба не волноваться.</b></h2>
--}}

<!--{{--#################### СООБЩЕНИЯ-ОШИБКИ ################--}}-->
@if(Request::has('needconfirm'))
	<div class="alert alert-danger text-center" style="margin-top:20px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<b>Требуется подтверждение!</b> 
		Мы отправили на твой E-mail письмо, содержащее ссылку для подтверждения. Пожалуйста нажми на неё.<br><br>
		Если ты вдруг не найдёшь письмо в своей почте, обязательно проверь папку "СПАМ" или "Нежелательная почта", потому что письмо может случайно туда попасть
	</div>
@endif

@if(Request::has('notfoundconfirm'))
	<div class="alert alert-danger text-center" style="margin-top:20px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<b>Внимание!</b> 
		Твоя ссылка для подтверждения уже устарела, либо она повредилась в процессе копирования.<br><br>
		Пожалуйста сделай новое <a href="javascript:;" class="button-forget alert-link" style="text-decoration:underline;">сброс пароля</a>
	</div>
@endif

@if(Request::has('confirmedbefore'))
	<div class="alert alert-danger text-center" style="margin-top:20px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<b>Внимание!</b> 
		Твой адрес E-mail уже был подтверждён ранее, и не требует повторного подвтерждения.<br><br>
		Если пароль был забыт, то можно сделать <a href="javascript:;" class="button-forget alert-link" style="text-decoration:underline;">сброс пароля</a>
	</div>
@endif

@if(Request::has('confirmed'))
	<div class="alert alert-danger text-center" style="margin-top:20px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<b>Поздравляем с успешной регистрацией!</b> 
		На твой E-mail только что был выслан пароль.<br><br>
		Если ты вдруг не найдёшь письмо в своей почте, обязательно проверь папку "СПАМ" или "Нежелательная почта", потому что письмо может случайно туда попасть
	</div>
@endif

@if(Request::has('reminded'))
	<div class="alert alert-danger text-center" style="margin-top:20px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<b>Внимание!</b> 
		На твой E-mail только что было отправлено письмо с новым паролем.<br><br>
		Если ты вдруг не найдёшь письмо в своей почте, обязательно проверь папку "СПАМ" или "Нежелательная почта", потому что письмо может случайно туда попасть
	</div>
@endif






<!--{{--#################### ВСЕ ОСТАЛЬНЫЕ ЛОГИНЫ-ПАРОЛИ ################--}}-->
    <form class="form-horizontal login-form" action="/auth/login" method="post" id="formLogin" name="formLogin">
        {{ csrf_field() }}
{{--        <input type="hidden" name="_back" value="/admin/281/site/settings.html"/> --}}

        <h3 class="font-blue">ВХОД</h3>
		<br>

        <div class="form-group">
            <label class="col-md-4 control-label">E-mail <span class="font-red-mint">*</span></label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-envelope"></i>
                    <input class="form-control" autocomplete="on" name="email" type="text"/>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Пароль <span class="font-red-mint">*</span></label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-key"></i>
                    <input class="form-control" autocomplete="off" name="password" type="password"/>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label" style="padding-top:0px;">
				<a href="javascript:;" class="button-forget" id="reminderActivate">Сбросить пароль?</a>
				<span class="visible-xs"><br></span>
			</label>
            <div class="col-md-8">
				<button type="submit" class="btn blue uppercase" style="width:100%; height:43px;">Вход</button>
            </div>
        </div>

	  <br>

@if( Config('auth.global.registration') )
        <div class="create-account" style="background-color: #FC0D1B;">
            <p>
                <a href="/reg" class="uppercase button-register" id="regActivate">Регистрация <span class="hidden-xs">нового пользователя</span></a>
            </p>
        </div>
@endif
    </form>










@if( Config('auth.global.registration') )
    <form class="form-horizontal register-form" action="/auth/register" method="post" id="formRegister" name="formRegister">
        {{ csrf_field() }}

        <h3 class="font-blue">РЕГИСТРАЦИЯ</h3>
		<br>

        <div class="form-group">
            <label class="col-md-4 control-label">Ваше имя <span class="font-red-mint">*</span></label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-user"></i>
                    <input class="form-control" autocomplete="off" name="name" type="text" placeholder="Введите ваше имя"/>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Ваш E-mail <span class="font-red-mint">*</span></label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-envelope"></i>
                    <input class="form-control" autocomplete="off" name="email" type="email" placeholder="Введите E-mail"/>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>

        {{--
        <div class="form-group">
            <label class="col-md-4 control-label">Кто пригласил?</label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-phone"></i>
                    <input class="form-control" autocomplete="off" name="phone_afillate" type="phone" value="" placeholder="Телефон пригласившего" maxlength="18"/>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>
        --}}
        <input name="phone_afillate" type="hidden" value=""/>

         <div class="form-group">
            <label class="col-md-4 control-label">&nbsp;</label>
            <div class="col-md-8">
                <div class="input-icon">
                    <label><input type="checkbox" name="oferta" value="on">
                        Я принимаю <a href="/oferta" target="_blank">договор оферты</a>
                        <span class="font-red-mint">*</span></label>
                </div>
                <label class="help-inline help-small no-left-padding hidden error"></label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">&nbsp;</label>
            <div class="col-md-8" style="padding-left:12px;">
            	<button type="submit" id="register-submit-btn" class="btn blue uppercase" style="width:100%; height:43px;">Регистрация</button>
            </div>
        </div>

	  <br>

        <div class="create-account clearfix">
            <p>
                <a href="/login" class="uppercase button-login">Вход <span class="hidden-xs">с логином и паролем</span></a>
            </p>
        </div>
    </form>
@endif












    <form class="form-horizontal forget-form" action="/auth/reminder" method="post" id="formRemindPassword" name="formRemindPassword">
        {{ csrf_field() }}

        <h3 class="form-title font-blue">СБРОСИТЬ ПАРОЛЬ</h3>
        <p>Введи свой E-mail, и на него будет отправлено письмо с новым паролем</p>

        <div class="form-group">
            <label class="col-md-4 control-label">E-mail <span class="font-red-mint">*</span></label>
            <div class="col-md-8">
                <div class="input-icon"><i class="fa fa-envelope"></i>
                    <input class="form-control" autocomplete="off" placeholder="Email" name="email" type="email"/>
					<label class="help-inline help-small no-left-padding error hidden" id="reminderError"></label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">&nbsp;</label>
            <div class="col-md-8">
				<button type="submit" class="btn blue uppercase" style="width:100%; height:43px;">ОТПРАВИТЬ</button>
            </div>
        </div>

		<br>
        <div class="create-account clearfix">
            <p>
                <a href="/login" class="uppercase button-login">Вход <span class="hidden-xs">с логином и паролем</span></a>
            </p>
        </div>
@if( Config('auth.global.registration') )
        <div class="create-account">
            <p>
                <a href="/reg" class="uppercase button-register">Регистрация <span class="hidden-xs">нового пользователя</span></a>
            </p>
        </div>
@endif
    </form>





</div>





<div class="page-auth"  style="width:auto; padding: 30px; margin: 0 auto; max-width:550px; ">
        <div class="form-group">
            <label class="col-md-4 control-label">
			<b>Или быстро войти без пароля</b> с помощью одной из твоих соцсетей
		</label>
            <div class="col-md-8">
			<div id="uLogin" data-ulogin="display=panel;theme=flat;sort=default;lang=ru;mobilebuttons=1;
			fields=email;
			optional=first_name,last_name,bdate,sex,phone,photo_big,city,country;
			providers=vkontakte,odnoklassniki,mailru,yandex,livejournal,
			google,googleplus,liveid,facebook,twitter,flickr,instagram,tumblr,vimeo,uid,webmoney,foursquare,
			youtube,lastfm,soundcloud,wargaming,steam,openid;
			hidden=none;
			redirect_uri={{ urlencode('https://' . $_SERVER['HTTP_HOST']) }}/ulogin;"></div>
            </div>
        </div>
</div>
<br><br><br>





</div>




<script src="/js/admin_jquery.js"></script>
<script src="/js/admin_bootstrap.js"></script>
<script src="/js/admin_app.js"></script>

<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {
	jQuery(function() {
		jQuery( "input[name$='phone_afillate']" ).mask("+0 (000) 000-00-00", {
			placeholder: "",
		});
	});
});
</script>

@if(isset($reg)) 
<script>
	jQuery('#regActivate').click(); 
</script>
@endif

@if(isset($reminder)) 
<script>
	jQuery('#reminderActivate').click(); 
</script>
@endif

<script src="//ulogin.ru/js/ulogin.js"></script>

</body>
</html>
