<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js"> <!--<![endif]--><head>
    <meta charset="utf-8"/>

	<title>@yield('title')</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>

	<link rel="shortcut icon" href="/favicon.png" type="image/png">

    <link href="/css/admin_bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_jquery.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_fonts.css" rel="stylesheet" type="text/css"/>
    <link href="/css/admin_app.css" rel="stylesheet" type="text/css"/>
    <link href="/css/public_tariffs.css" rel="stylesheet" type="text/css" />

</head>

<body class="page-header-fixed page-content-white">
<div class="page-wrapper admin-page">
 
 
 <a name="top"></a>
 
 
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner">

                	@if(Config('global.logo_title.img'))
			<div class="page-logo">
				<a href="/admin"><img src="{{ Config('global.logo_title.img') }}" alt="" style="
				@if(Config('global.logo_title.width')) width: {{ Config('global.logo_title.width') }}; @endif
				@if(Config('global.logo_title.height')) height: {{ Config('global.logo_title.height') }}; @endif
				"></a>
			</div>
		    	@endif

			<div class="slogan">
				{{ Config('global.project.title') }}
			</div>

            <a href="javascript:;" class="menu-toggler responsive-toggler btn-nvabar" data-toggle="collapse"
               data-target=".navbar-collapse" style="margin-right:20px;">
                <span></span>
            </a>

		<style>
		@media (max-width: 415px) {
		.bigg {font-size:11px!important;} 
		}
		@media (max-width: 376px) {
		.user_ava_box {display:none!important;}
		}
		</style>

            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    <li class="dropdown dropdown-user" data-toggle="tooltip" data-placement="bottom" data-original-title="Текущий баланс - {{ $currency[$user->currency]['Name']}}" title="Текущий баланс - {{ $currency[$user->currency]['Name']}}">
                        <a href="/admin/billing/history" class="dropdown-toggle" data-close-others="true" style="padding: 14px 8px 11px 8px;">
					<span class="username bigg" style="color:#ffffff; font-size:22px; font-weight:bold; padding-right:0px;">{{ floor( ($balance / $currency[$user->currency]['Value']) * $currency[$user->currency]['Nominal']  ) }}</span>
					<span class="username" style="padding-left:0px;">
						<b>{{ $user->currency }}</b>
					</span>
                        </a>
                    </li>

                    <li class="dropdown dropdown-user hidden-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Последние новости" title="Последние новости">
                        <a href="/admin/news" class="dropdown-toggle" data-close-others="true" style="padding: 16px 8px 13px 8px;">
					<i class="icon-flag" style="font-size:18px;"></i>
                        </a>
                    </li>

                    <li class="dropdown dropdown-user hidden-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Последние новости" title="Есть идея?">
                        <a href="/admin/support/service" class="dropdown-toggle" data-close-others="true" style="padding: 16px 8px 13px 8px;">
					<i class="icon-bulb" style="font-size:18px; color:#FFFF00;"></i>
                        </a>
                    </li>

                    <li class="dropdown dropdown-user user_ava_box">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				    <img alt="" class="img-circle" src="{{ User::getAvatar($user->id, 'small') }}">
                            <span class="username username-hide-on-mobile">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default" id="dropdownUser">
                            <li><a href="/admin/settings/personal"><i class="icon-user"></i> Мой профиль</a></li>
                            <li class="divider"></li>
                            <li><a href="/auth/logout" id="buttonLogout"><i class="icon-key"></i> Выход</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    <div class="clearfix"></div>







    <div class="page-container">
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse hidden-xs hidden-sm" id="page-sidebar">
                @include('admin.menu')
                @if(Config('global.logo_sidebar.img'))
		    <div class="text-center">
				<img src="{{ Config('global.logo_sidebar.img') }}" alt="" style="
				@if(Config('global.logo_sidebar.width')) width: {{ Config('global.logo_sidebar.width') }}; @endif
				@if(Config('global.logo_sidebar.height')) height: {{ Config('global.logo_sidebar.height') }}; @endif
				margin:12px;">
		    </div>
		    @endif
            </div>
        </div>
        @yield('main')
    </div>









<!-- {{--
        <div class="page-footer">
            <div class="page-footer-inner">
                <small>Сюда можно включить текст</small>
            </div>
        </div>
--}} -->

</div>
<div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>ВВЕРХ</span></a></div>



<script type="text/javascript" src="/js/admin_jquery.js"></script>
<script type="text/javascript" src="/js/admin_bootstrap.js"></script>
<script type="text/javascript" src="/js/admin_app.js"></script>
<script>
App.menu.init( {!! $menu !!} );

jQuery(".icon-action-undo").css('color', '#FFFFFF').next().css('font-weight', 'bold').css('color', '#FFFFFF');

(function($){
	"use strict";
	var POTENZA = {};

	var $window = $(window), $document = $(document);

	/*************************
	     Back to top
	*************************/
	POTENZA.goToTop = function () {
	  var $goToTop = jQuery('#back-to-top');
		$goToTop.hide();
		  $window.scroll(function(){
		    if ($window.scrollTop()>100) $goToTop.fadeIn();
		    else $goToTop.fadeOut();
		});
	    $goToTop.on("click", function () {
		  jQuery('body,html').animate({scrollTop:0},1000);
		  return false;
	    });
	}

	$document.ready(function () {
		POTENZA.goToTop()
	});
})(jQuery);

</script>
@stack('scripts')

</body>
</html>
