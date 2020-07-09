@section('title', 'Добавить новый токен')
@extends('admin.template')

@section('main')

<div class="page-content-wrapper">
	<div class="page-content">
                    <div class="alert-area"></div>


            <div class="row">
                <div class="col-xs-12">
                    <h3 class="page-title">Добавить новый токен для авторизации по API</h3>
                    <div class="img-glyph"><span class="icon-question"></span></div>
				<p style="margin-top:0px;">Здесь можно сгенерировать токен для внешнего приложения или сайта, получающего доступ к системе через API. Будьте внимательны - каждый токен это полноценный доступ к вашей системе "извне", с полными правами по извлечению ваших данных или по размещению их в базе.</p>
                </div>
            </div>



		<div class="row">









<!--{{-- ############## ШАГ 1 ЗАПРОСА НОВОГО ТОКЕНА ДЛЯ API ЧУЖОГО САЙТА ############## --}}-->
@if ($step==1)
                <div class="col-xs-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Параметры сайта или приложения, которым предоставляется доступ</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/configer/token" class="form-horizontal" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-xs-12">Условное название сайта или приложения <span class="font-red-mint">*</span></label>
                                    <div class="col-xs-12">
                                            <input type="text" class="form-control" name="name" value="" required="true" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-12">Полный адрес страницы этого сайта с подтверждением токена согласно документации к API <span class="font-red-mint">*</span><br />
						<B>Например вида https://{{ $_SERVER['SERVER_NAME'] }}/examples/api/test_callback_php.php</B></label>
                                    <div class="col-xs-12">
                                            <input type="text" class="form-control" name="url"  value="" required="true" />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ПЕРЕЙТИ К ШАГУ 2</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
@endif
<!--{{-- ############################################################################# --}}-->










<!--{{-- ############## ШАГ 2 ЗАПРОСА НОВОГО ТОКЕНА ДЛЯ API ЧУЖОГО САЙТА ############## --}}-->
@if ($step==2)
                <div class="col-xs-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">ШАГ 2. Параметры для указания в программах авторизации сайта</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/configer/token" class="form-horizontal" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="step"  value="3" />

                                <div class="form-group">
                                    <label class="col-xs-12">Полный адрес страницы этого сайта с подтверждением токена согласно документации к API</label>
                                    <div class="col-xs-12" style="font-size:24px;"><b>{{ $oauth_client_redirect }}</b></div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-12">Скопируйте и запишите ID сайта</label>
                                    <div class="col-xs-12" style="font-size:24px;"><b>{{ $oauth_client_id }}</b></div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-12">Скопируйте и запишите секретный ключ сайта для генерации запроса</label>
                                    <div class="col-xs-12" style="font-size:24px;"><b>{{ $oauth_client_secret }}</b></div>
                                </div>

                                <div class="form-actions">
					 	<p class="font-red-thunderbird text-left">Внимание! Перед переходом к следующем шагу и перед нажатием кнопки, убедитесь, что на стороне сайта, которому предоставляется доступ:</p>
							<ol>
								<li class="font-red-thunderbird text-left">существует указанный  URL для подтверждения токена, и он работает согласоно документации к API</li>
								<li class="font-red-thunderbird text-left">в программе по этому адресу прописан вышеуказанный "ID сайта"</li>
								<li class="font-red-thunderbird text-left">в программе в этом URL прописан указанный "Секретный ключ"</li>
							</ol>
						<p class="font-red-thunderbird text-left">Сразу же после отправки формы вы будете перенаправлены на эту страницу для генерации запроса, и только после этого будет сгенерирован токен.</p>

                                    <a class="btn btn-primary" href="{{ $oauth_url }}" target="_blank"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ПЕРЕЙТИ К АВТОРИЗАЦИИ</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
@endif
<!--{{-- ############################################################################# --}}-->










		</div>



	</div>
</div>
@endsection
