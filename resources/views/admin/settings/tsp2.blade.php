@section('title', 'Профиль торгово-сервисного предприятия (ТСП)')
@extends('admin.template')

@section('main')
    <div class="page-content-wrapper">
        <div class="page-content">
		<div class="alert-area"></div>




            <div class="row">





                <div class="col-xs-12 col-lg-6">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-user font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Общие данные</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/personal" id="formUpdateProfile" class="form-horizontal" name="formUpdateProfile" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Фамилия&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-user"></i>
                                            <input type="text" class="form-control" name="name_family" value="{{ $user->name_family }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Имя&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-user"></i>
                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Отчество&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-user"></i>
                                            <input type="text" class="form-control" name="name_patronymic" value="{{ $user->name_patronymic }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Пол&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-symbol-male"></i>
                                            <select class="form-control" name="sex"/>
							  	<option value=""></option>
							  	<option value="M" @if ($user->sex=='M') selected @endif>Мужской</option>
							  	<option value="F" @if ($user->sex=='F') selected @endif>Женский</option>
							  </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Телефон&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-call-in"></i>
                                            <input type="text" class="form-control" name="phone"  value="{{ $user->phone }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">E-mail&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-envelope"></i>
                                        	<input class="form-control" rows="3" name="email"  value="{{ $user->email }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Фото или аватар&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-envelope"></i>
                                        	<input class="form-control" type="file" name="photo" />
                                        </div>
                                    </div>
                                </div>


<br />

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Баланс ведётся в валюте&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">

							<select class="form-control" name="currency">
								<option value=""></option>
								<option value="RUR" {{ $user->currency == "RUR" ? 'selected' : '' }}>Рубли РФ</option>
								<option value="USD" {{ $user->currency == "USD" ? 'selected' : '' }}>Доллары США</option>
								<option value="EUR" {{ $user->currency == "EUR" ? 'selected' : '' }}>Евро</option>
							</select>

                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




<!--{{--
                <div class="col-xs-12 col-lg-6">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-book-open font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Персональные данные</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/personalext" id="formUpdateProfileExt" class="form-horizontal" name="formUpdateProfileExt" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Дата рождения&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                          <input type="text" class="form-control" name="birthday" value="{{ $user->birthday }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Паспорт серия и номер&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                          <input type="text" class="form-control" name="passport" value="{{ $user->passport }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Изображение паспорта&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
							<p class="help-block" style="color:#333333;">Главная страница</p>
                                          <input type="file" class="form-control" name="passport_file1" value=""  data-toggle="tooltip" data-placement="top" title="" data-original-title="Допустимы файлы только формата JPG или PDF"/>
							<br />
							<p class="help-block" style="color:#333333;">Страница с пропиской</p>
                                          <input type="file" class="form-control" name="passport_file2" value=""  data-toggle="tooltip" data-placement="top" title="" data-original-title="Допустимы файлы только формата JPG или PDF"/>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ И ОТПРАВИТЬ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
		<div class="row">



                <div class="col-xs-12 col-lg-6">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-envelope-open font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Почтовый адрес</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/post-address" id="formUpdateAddress" class="form-horizontal" name="formUpdateAddress" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Страна&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        	<input class="form-control" rows="3" name="country"  value="{{ $user->country }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Почтовый индекс&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        	<input class="form-control" rows="3" name="zip"  value="{{ $user->zip }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Населённый пункт, регион&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                            <input type="text" class="form-control" name="city"  value="{{ $user->city }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Улица, дом, квартира&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                            <input type="text" class="form-control" name="address" value="{{ $user->address }}" />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ&nbsp;АДРЕС</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




                <div class="col-xs-12 col-lg-6">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-picture font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Адрес прописки</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/post-address" id="formUpdateAddress" class="form-horizontal" name="formUpdateAddress" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Страна&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        	<input class="form-control" rows="3" name="country"  value="{{ $user->country }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Почтовый индекс&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        	<input class="form-control" rows="3" name="zip"  value="{{ $user->zip }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Населённый пункт, регион&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                            <input type="text" class="form-control" name="city"  value="{{ $user->city }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Улица, дом, квартира&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                            <input type="text" class="form-control" name="address" value="{{ $user->address }}" />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ&nbsp;АДРЕС</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




		</div>
            <div class="row">
--}}-->



                <div class="col-xs-12 col-lg-6">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-key font-red"></i>
                                <span class="caption-subject font-red uppercase">Изменение пароля</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/post-password" id="formUpdatePassword" class="form-horizontal" name="formUpdatePassword" method="post">
                                {{ csrf_field() }}

<!--{{--
                                <div class="alert hide">
                                    <span></span>
                                </div>
--}}-->
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Новый пароль&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-lock"></i>
                                            <input type="password" class="form-control" name="password" id="update-password" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Повтор пароля&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-md-7">
                                        <div class="input-icon">
                                            <i class="icon-lock"></i>
                                            <input type="password" class="form-control" name="password_confirmation" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn red btn-adaptive"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ИЗМЕНИТЬ ПАРОЛЬ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>






            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
App.components.admin_settings.init();
</script>
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {
	jQuery(function() {
		jQuery( "input[name$='phone']" ).mask("+0 (000) 000-00-00", {
			placeholder: "+0 (000) 000-00-00",
		});
		jQuery( "input[name$='birthday']" ).mask("00.00.0000", {
			placeholder: "00.00.0000",
		});
		jQuery( "input[name$='passport']" ).mask("00 00 000000", {
			placeholder: "00 00 000000",
		});
		jQuery( "input[name$='zip']" ).mask("000000", {
			placeholder: "000000",
		});
	});
});

</script>
@endpush