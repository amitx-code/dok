@section('title', 'Профиль торгово-сервисного предприятия (ТСП)')
@extends('admin.template')

@section('main')

    <div class="page-content-wrapper">
        <div class="page-content">
		<div class="alert-area"></div>







<div class="row">
	<div class="col-xs-12">
         <div class="category-list">
			<a href="/admin/settings/tsp" class="btn
			@if (empty($request['type'])) white font-dark @else btn-success @endif
			">Для юр.лиц</a>
			<a href="/admin/settings/tsp?type=2" class="btn 
			@if (!empty($request['type']) && $request['type']=='2') white font-dark @else btn-success @endif
			">Для ИП</a> 
          </div>
	</div>
</div>






@if (empty($request['type']))

            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-doc font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Данные юридичекого лица</span>
                            </div>
                        </div>
                        <div class="portlet-body form">

					<form action="/admin/settings/tsp" id="formUpdateDefault" class="form-horizontal" name="formUpdateDefault" method="post">
					 {{ csrf_field() }}
					<input type="hidden" name="type" value="" />
					<input type="hidden" name="part" value="1" />

                                <div class="row">
						  <div class="col-md-6">

							  <div class="form-group">
								<label class="col-md-6 control-label">Полное наименование&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_name")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Сокращенное наименование&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_name_short")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">ИНН&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_inn")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">КПП&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_kpp")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">ОГРН&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_ogrn")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Код ОКВЭД&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_okved")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Код ОКПО&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_okpo")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>

						  </div>
						  <div class="col-md-6">

							  <div class="form-group">
								<label class="col-md-6 control-label">Почтовый адрес&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_address_post")
								<div class="col-md-6">  
									  <textarea row="4" class="form-control" name="{{ $name }}">{{ $user->$name }}</textarea>
								</div>
							  </div>

							  <div class="form-group">
								<label class="col-md-6 control-label">Юридический адрес&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_address_ur")
								<div class="col-md-6">  
									  <textarea row="4" class="form-control" name="{{ $name }}">{{ $user->$name }}</textarea>
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Адрес E-mail&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_email")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Генеральный директор&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_director")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Банковские реквизиты&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_bank")
								<div class="col-md-6">  
									  <textarea row="4" class="form-control" name="{{ $name }}">{{ $user->$name }}</textarea>
								</div>
							  </div>

						  </div>
					</div>


                                <div class="row">
                                    <div class="col-md-6 control-label">Нужна интеграция с кассой? <a href="/admin/support/service">Напишите нам!</a></div>
                                    <div class="col-md-6">
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ</button>
							  </div>
                                    </div>
                                </div>

					</form>

                        </div>
                    </div>
                </div>
		</div>



            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-doc font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Документы юридичекого лица</span>
                            </div>
                        </div>
                        <div class="portlet-body form">

					<form action="/admin/settings/tsp" id="formUpdateDefault1" class="form-horizontal" name="formUpdateDefault1" method="post">
					 {{ csrf_field() }}
					<input type="hidden" name="type" value="" />
					<input type="hidden" name="part" value="2" />

                                <div class="form-group">
                                    <div class="col-xs-6 control-label">Допустимые форматы файлов</div>
                                    <div class="col-xs-6"><p class="form-control-static">PDF, JPG, PNG, DOC, DOCX, ZIP</p></div>
					  </div>
                                <div class="form-group">
                                    <div class="col-xs-6 control-label">Максимальный размер каждого файла</div>
                                    <div class="col-xs-6"><p class="form-control-static">Не более 5 Мб</p></div>
					  </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство регистрации  в качестве юридического лица (ОГРН) (лист уведомления)&nbsp;<span class="font-red-mint">*</span></label>
						@php ($name = "file_ogrn")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство о постановке на налоговый учет&nbsp;<span class="font-red-mint">*</span></label>
						@php ($name = "file_ifns")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Приказ о назначении руководителя&nbsp;<span class="font-red-mint">*</span></label>
						@php ($name = "file_prikaz")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство на право собственности (иного права - владения, пользования и т.д.) либо договор аренды нежилого помещения (фактическое местонахождения ТСП)&nbsp;<span class="font-red-mint">*</span></label>
 						@php ($name = "file_arenda")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Доверенность уполномоченного лица (в случае подписания документов уполномоченным лицом)</label>
  						@php ($name = "file_dover")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Устав организации&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_ustav")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Карточка клиента с указанием банковских реквизитов&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_karta")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>





                                <div class="row">
                                    <div class="col-md-6 control-label">Нужна интеграция с кассой? <a href="/admin/support/service">Напишите нам!</a></div>
                                    <div class="col-md-6">
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ</button>
							  </div>
                                    </div>
                                </div>

					</form>

                        </div>
                    </div>
                </div>
		</div>

@endif









@if (!empty($request['type']) && $request['type']=='2')


            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-doc font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Данные индивидуального предпринимателя</span>
                            </div>
                        </div>
                        <div class="portlet-body form">

					<form action="/admin/settings/tsp" id="formUpdateDefault" class="form-horizontal" name="formUpdateDefault" method="post">
					 {{ csrf_field() }}
					<input type="hidden" name="type" value="2" />
					<input type="hidden" name="part" value="1" />

                                <div class="row">
						  <div class="col-md-6">

							  <div class="form-group">
								<label class="col-md-6 control-label">Наименование&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_name")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">ИНН&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_inn")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
				
							  <div class="form-group">
								<label class="col-md-6 control-label">ОГРНИП&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_ogrn")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
		
						  </div>
						  <div class="col-md-6">

							  <div class="form-group">
								<label class="col-md-6 control-label">Адрес&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_address_ur")
								<div class="col-md-6">  
									  <textarea row="4" class="form-control" name="{{ $name }}">{{ $user->$name }}</textarea>
								</div>
							  </div>
		
							  <div class="form-group">
								<label class="col-md-6 control-label">Адрес E-mail&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_email")
								<div class="col-md-6">  
									  <input type="text" class="form-control" name="{{ $name }}"  value="{{ $user->$name }}" />
								</div>
							  </div>
				
							  <div class="form-group">
								<label class="col-md-6 control-label">Банковские реквизиты&nbsp;<span class="font-red-mint">*</span></label>
								@php ($name = "tsp_bank")
								<div class="col-md-6">  
									  <textarea row="4" class="form-control" name="{{ $name }}">{{ $user->$name }}</textarea>
								</div>
							  </div>

						  </div>
					</div>


                                <div class="row">
                                    <div class="col-md-6 control-label">Нужна интеграция с кассой? <a href="/admin/support/service">Напишите нам!</a></div>
                                    <div class="col-md-6">
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ</button>
							  </div>
                                    </div>
                                </div>

					</form>

                        </div>
                    </div>
                </div>
		</div>



            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-doc font-blue-soft"></i>
                                <span class="caption-subject font-blue-soft uppercase">Документы индивидуального предпринимателя</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form action="/admin/settings/tsp" id="formUpdateDefault1" class="form-horizontal" name="formUpdateDefault1" method="post">
                                {{ csrf_field() }}
					  <input type="hidden" name="type" value="2" />
					  <input type="hidden" name="part" value="2" />

                                <div class="form-group">
                                    <div class="col-xs-6 control-label">Допустимые форматы файлов</div>
                                    <div class="col-xs-6"><p class="form-control-static">PDF, JPG, PNG, DOC, DOCX, ZIP</p></div>
					  </div>
                                <div class="form-group">
                                    <div class="col-xs-6 control-label">Максимальный размер каждого файла</div>
                                    <div class="col-xs-6"><p class="form-control-static">Не более 5 Мб</p></div>
					  </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство регистрации в качестве индивидуального предпринимателя&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_ogrn")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство о постановке на налоговый учет&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_ifns")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Свидетельство на право собственности (иного права - владения, пользования и т.д.) либо договор аренды нежилого помещения (фактическое местонахождения ТСП)&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_arenda")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-6 control-label">Карточка клиента с указанием банковских реквизитов&nbsp;<span class="font-red-mint">*</span></label>
  						@php ($name = "file_karta")
                                    <div class="col-md-2"><p class="form-control-static">
							@if ($user->$name)<a href="/admin/settings/tsp/download/{{ $user->$name }}">Уже загружено</a><br />@endif
                                    </p></div>
                                    <div class="col-md-4">  
                                        	<input class="form-control" type="file" name="{{ $name }}" />
                                    </div>
                                </div>





                                <div class="row">
                                    <div class="col-md-6 control-label">Нужна интеграция с кассой? <a href="/admin/support/service">Напишите нам!</a></div>
                                    <div class="col-md-6">
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ</button>
							  </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
		</div>
@endif









        </div>
    </div>
@endsection
@push('scripts')
<script>
App.components.admin_settings.init();
</script>
@endpush