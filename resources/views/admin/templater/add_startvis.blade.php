@section('title', 'Старт создания документа')
@extends('admin.template')

@section('main')


<style>
.right-left {
	text-align:right;
}
@media only screen and (max-width : 768px) {
.right-left {
	text-align:left;
}
}
.vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
}

#contentDoc h1 {
	font-size:150%;
	font-weight: bold;
	text-align: center;
}
#contentDoc h2 {
	font-size:120%;
	font-weight: bold;
	text-align: center;
}
#contentDoc h3 {
	font-size:110%;
	font-weight: bold;
}
#contentDoc .field {
	background-color: #dddddd;
	color: #999999;
	padding: 0 5px;
}
#contentDoc .field_active {
	background-color: #ffffff;
	color: #FF0000;
}

</style>






<div class="page-content-wrapper">
	<div class="page-content">
		<div class="alert-area"></div>



		<div class="row">
			<div class="col-xs-12">
				<h1 class="page-title" style="margin-bottom:0px !important;">
					{{ $template->name }}
					@if ($template->customer) 	/ {{ $template->customer }} @endif
					@if ($template->period) 	/ {{ $template->period }} @endif
					@if ($template->type) 		/ {{ $template->type }} @endif
					@if ($template->more) 		/ {!! $template->more !!} @endif
				</h1>
				@if ($template->description)
					<p style="margin:0px;">{{ $template->description }}</p>
				@endif
			</div>
		</div>
		<br />








		<form action="/admin/templater/add/{{ $template->id }}/generate" id="formDocument" name="formDocument" class="form-horizontal" enctype="multipart/form-data" method="post">
			{{ csrf_field() }}




			<div class="row">






				<div id="contentFields" class="col-xs-6 col-sm-4 col-md-4" style="height:800px; overflow-y:scroll;">






					@if (count($boxes) > 0 && count($fields) > 0)
						<div class="portlet light">
							<div class="portlet-body form">

								<div class="row">
									<div class="col-xs-12">
										<a href="/admin/templater/add/{{ $template->id }}/startvis?fill=last" class="btn btn-xs blue"
										   @if (!session("fill")) disabled onclick="alert('Сначала нужно заполнить хотя бы один предыдущий документ'); return false;" @endif>
											ПРЕДЫДУЩИЕ ДАННЫЕ &nbsp; <i class="icon-heart"></i>
										</a>
									</div>
									<div class="col-xs-12 small vcenter">
										Заполнить форму данными, которые заполнялись в предыдущий последний раз
									</div>
								</div>

								<div class="visible-xs"><br /></div>
								<div class="row" style="margin-top:5px;">
									<div class="col-xs-12">
										<a href="/admin/templater/add/{{ $template->id }}/example" class="btn btn-xs yellow">
											ИЗ ДРУГОГО ДОКУМЕНТА &nbsp; <i class="icon-plus"></i>
										</a>
									</div>
									<div class="col-xs-12 small vcenter">
										Заполнить форму данными взятыми из другого документа на выбор
									</div>
								</div>

								<div class="visible-xs"><br /></div>
								<div class="row" style="margin-top:5px;">
									<div class="col-xs-12">
										<a href="/admin/templater/add/{{ $template->id }}/startvis" class="btn btn-xs red">
											ОЧИСТИТЬ &nbsp; <i class="icon-ban"></i>
										</a>
									</div>
									<div class="col-xs-12 small vcenter">
										Привести форму в первоначальное состояние
									</div>
								</div>

							</div>
						</div>
					@endif




					<div class="row">


						<div class="col-xs-12">

							@foreach ($boxes as $box)

								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption">
											<span class="caption-subject font-dark bold uppercase">{{ $box->name }}</span>
											<span class="caption-helper">{{ $box->description }}</span>
										</div>
									</div>

									<div class="portlet-body form">





									<!--{{-- ############################ ВЫВОД В ГОРИЗОНТАЛЬНЫХ СТРОЧКАХ ########################## --}}-->
										@if ($box->view == "rows")

											<div class="form-group">
												@foreach ($fields as $field)
													@if ($field->box == $box->id)

														<label class="@if ($field->size == "small") col-xs-2 @else col-xs-4 @endif">
															{{ $field->description }} @if ($field->required) <span class="font-red-mint">*</span>@endif
														</label>

													@endif
												@endforeach

											</div>

											@if ($request->fill=="last" && session("CLONE_" . $box->id . "_TOTAL") )
												<?PHP $cloneCount = session("CLONE_" . $box->id . "_TOTAL"); ?>
											@elseif (is_numeric($request->fill) && isset($example[ "CLONE_" . $box->id . "_TOTAL" ]) )
												<?PHP $cloneCount = $example[ "CLONE_" . $box->id . "_TOTAL" ]; ?>
											@else
												<?PHP $cloneCount=3; ?>
											@endif

											@for ($i = 1; $i <= $cloneCount; $i++)
												<?PHP $dop = "#".$i; ?>
												<div class="form-group" data-count="{{ $i }}">

													@foreach ($fields as $field)
														@if ($field->box == $box->id)

															@if ($i == 1 && empty($first))<?php $first=1; ?>
															<input type="hidden" name="CLONE_{{ $box->id }}_NAME" value = "{{ $field->name }}"/>
															@endif

															@if ($field->special)<input type="hidden" name="{{ $field->special }}" value = "{{ $field->name }}"/>@endif

															<div class="@if ($field->size == "small") col-xs-2 @else col-xs-4 @endif"
																 @if ($field->tooltip)
																 data-toggle="tooltip" data-placement="top" title='{{ $field->tooltip }}'
																	@endif
															>

																@if ($field->control == "input")
																	<input type="text" class="form-control field_input_fill @if ($field->mask == '# ##0,00') text-right @endif "
																		   name="{{ $field->name . $dop }}" data-count="{{ $i }}" data-basename="{{ $field->name }}"
																		   data-label='{{ $field->description }}'
																		   value="@if (
									$request->fill=="last" &&
									session($field->name . $dop)
								){{ session($field->name . $dop) }}@elseif (
									is_numeric($request->fill) &&
									isset($example[$field->name . $dop])
								){{ $example[$field->name . $dop] }}@elseif (
									$field->autofill == 'today'
								){{ date('d.m.Y') }}@elseif (
									$field->autofill
								){{ $field->autofill }}@endif"
																		   @if ($field->required) required @endif />

																@elseif ($field->control == "select")
																	<select class="form-control field_select_fill" name="{{ $field->name . $dop }}"
																			data-count="{{ $i }}" data-basename="{{ $field->name }}" >
																		@foreach (explode("|", $field->autofill) as $value)
																			<option value="{{ trim($value) }}" @if (
										$request->fill=="last" &&
										session($field->name . $dop) == trim($value)
									) selected @elseif (
										is_numeric($request->fill) &&
										isset($example[$field->name . $dop]) &&
										$example[$field->name . $dop] == trim($value)
									) selected @elseif (
										$request->fill!="last" &&
										$loop->first
									) selected @endif
																			>{!! trim($value) !!}</option>
																		@endforeach
																	</select>

																<!--{{-- чекбоксы и радиобаттоны удалены отсюда --}}-->

																@endif
															</div>

														@endif
													@endforeach
												</div>
											@endfor

											<div id="rowAddPlace"></div>

											<div class="form-group">
												<div class="col-xs-12 text-right">
													<a href="#" class="btn btn-xs red" id="rowDelete">
														УДАЛИТЬ СТРОЧКУ &nbsp; <i class="icon-ban"></i></a> &nbsp;
													<a href="#" class="btn btn-xs blue" id="rowAdd">
														ДОБАВИТЬ СТРОЧКУ &nbsp; <i class="icon-plus"></i></a>
												</div>
											</div>

											<input type="hidden" name="CLONE_{{ $box->id }}_TOTAL" id="cloneTotal" value = "{{ $cloneCount }}"/>







										@else
										<!--{{-- ############################ ОБЫЧНЫЙ ПОСТРОЧНЫЙ ВЫВОД ########################## --}}-->
											@foreach ($fields as $field)
												@if ($field->box == $box->id)

													@if ($field->special)<input type="hidden" name="{{ $field->special }}" value = "{{ $field->name }}"/>@endif

													<div class="form-group">
														<label class="col-xs-12">
															{{ $field->description }} @if ($field->required) <span class="font-red-mint">*</span>@endif
														</label>
														<div class="@if ($field->size == "small") col-xs-12 @else col-xs-12 @endif"
															 @if ($field->tooltip)
															 data-toggle="tooltip" data-placement="top" title='{{ $field->tooltip }}'
																@endif
														>

															@if ($field->control == "input")
																<input type="text" class="form-control field_input_fill @if ($field->mask == '# ##0,00') text-right @endif "
																	   name="{{ $field->name }}" data-label='{{ $field->description }}'
																	   value="@if (
									$request->fill=="last" &&
									session($field->name)
								){{ session($field->name) }}@elseif (
									is_numeric($request->fill) &&
									isset($example[$field->name])
								){{ $example[$field->name] }}@elseif (
									$field->autofill == 'today'
								){{ date('d.m.Y') }}@elseif (
									$field->autofill
								){{ $field->autofill }}@endif"
																	   @if ($field->required) required @endif />

															@elseif ($field->control == "select")
																<select class="form-control field_select_fill" name="{{ $field->name }}">
																	@foreach (explode("|", $field->autofill) as $value)
																		<option value="{{ trim($value) }}" @if (
										$request->fill=="last" &&
										session($field->name) == trim($value)
									) selected @elseif (
										is_numeric($request->fill) &&
										isset($example[$field->name]) &&
										$example[$field->name] == trim($value)
									) selected @elseif (
										$request->fill!="last" &&
										$loop->first
									) selected @endif
																		>{!! trim($value) !!}</option>
																	@endforeach
																</select>

															@elseif ($field->control == "checkbox")
																<div style="padding-top:7px;"></div>
																@foreach (explode("|", $field->autofill) as $value)
																	<label class="mt-checkbox col-xs-12">
																		<input type="checkbox" name="{{ $field->name }}[]"
																			   value="{{ trim($value) }}" @if (
										$request->fill=="last" &&
										mb_strpos( " ".session($field->name)." ", trim($value) )
									) checked @elseif (
										is_numeric($request->fill) &&
										isset($example[$field->name]) &&
										mb_strpos( " ".$example[$field->name]." ", trim($value) )
									) checked @endif
																		> {!! trim($value) !!}
																		<span></span>
																	</label>
																@endforeach

															@elseif ($field->control == "radio")
																<div style="padding-top:7px;"></div>
																@foreach (explode("|", $field->autofill) as $value)
																	<label class="mt-radio col-xs-12">
																		<input type="radio" name="{{ $field->name }}[]"
																			   value="{{ trim($value) }}" @if (
										$request->fill=="last" &&
										mb_strpos( " ".session($field->name)." ", trim($value) )
									) checked @elseif (
										is_numeric($request->fill) &&
										isset($example[$field->name]) &&
										mb_strpos( " ".$example[$field->name]." ", trim($value) )
									) checked @elseif (
										$request->fill!="last" &&
										$loop->first
									) checked @endif
																		> {!! trim($value) !!}
																		<span></span>
																	</label>
																@endforeach

															@elseif ($field->control == "file")
																<div class="input-group">
																	<input type="file" class="form-control"
																		   name="{{ $field->name }}" data-label='{{ $field->description }}'
																		   value=""
																		   @if ($field->required) required @endif />
																	<span class="input-group-btn">
									<button class="btn default clearFile" type="button">X</button>
								</span>
																</div>

															@endif
														</div>
													</div>

												@endif
											@endforeach






										<!--{{-- ########################################## /// ######################################## --}}-->
										@endif







									</div>
								</div>

							@endforeach

						</div>


					</div>
				</div>




				<div id="contentDoc" class="col-xs-6 col-sm-8 col-md-8" style="height:800px; overflow-y:scroll;">
					<div class="portlet light">
						<div class="portlet-body">
							{!! $doctext !!}
						</div>
					</div>
				</div>



			</div>



			@if (count($boxes) > 0 && count($fields) > 0)
				<br />
				<div class="text-center">
					<button id="buttonStart" class="btn btn-lg btn-primary" style="padding:16px; font-size:24px;">
						<i class="icon-check"></i> &nbsp; СОЗДАТЬ ДОКУМЕНТ
					</button>
					<br /><br />
				</div>
			@endif


		</form>






	</div>
</div>





@endsection
@push('scripts')
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script>

function isValidDate(val) {
  var val_r = val.split(".");
  var curDate = new Date(val_r[2], (val_r[1]-1), val_r[0]);
  return (
    curDate.getFullYear() == val_r[2]
    && curDate.getMonth() == (val_r[1]-1)
    && curDate.getDate() == val_r[0]
  );
}


jQuery(document).ready(function() {
	jQuery(function() {


		jQuery("#formDocument").on("submit", function(){
			jQuery("#buttonStart").prop("disabled", true);
		});

		jQuery(".clearFile").on("click", function(){
			jQuery(this).parent().parent().find("input").val("");
		});



		///* {{-- документация: http://igorescobar.github.io/jQuery-Mask-Plugin/docs.html --}} */
		@foreach ($fields as $field)

		@if ($field->mask)
		jQuery( "input[name$='{{ $field->name }}']" ).mask("{{ $field->mask }}", {
			placeholder: "{!! str_replace('0', '_', str_replace('#', '_', $field->mask)) !!}",
		});
		@endif

		@if ($field->mask == "00.00.0000")
		jQuery( "input[name$='{{ $field->name }}']" ).change(function() {
			var test = isValidDate(  jQuery(this).val()  );
			if (test == false) {
				var name = jQuery(this).attr("data-label");
				alert('Неправильно введена дата в поле "' + name + '"')
				jQuery(this).val('');
				jQuery(this).focus();
			}
		});
		@endif

		@if ($field->mask == "# ##0,00")
		jQuery( "input[name$='{{ $field->name }}']" ).mask("{{ $field->mask }}", {
			reverse: true
		});
		@endif

		@endforeach



		///* {{--Получение масок для тиражиуемых строчек-полей - да, сделано дебильно, но работает и нет времени исправлять --}} */
		@foreach ($boxes as $box)
		@if ($box->view == "rows")
		@for ($i = 1; $i <= $cloneCount; $i++)
		<?PHP $dop = "#".$i; ?>
		@foreach ($fields as $field)
		@if ($field->box == $box->id && $field->control == "input" && $field->mask == "# ##0,00")

		jQuery( "input[name$='{{ $field->name . $dop }}']" ).mask("{{ $field->mask }}", {
			placeholder: "{!! str_replace('0', '_', str_replace('#', '_', $field->mask)) !!}",
			reverse: true
		});

		@endif
		@endforeach
		@endfor
		@endif
		@endforeach



		///* {{-- Кнопки добавления и удаления иражиуемых строчек-полей --}} */
		jQuery("#rowAdd").click(function(){
			var newCount = jQuery("#cloneTotal").val()*1 + 1;
			jQuery("#cloneTotal").val(newCount);

			var example ='<div class="form-group" data-count="' + newCount + '">' + jQuery("#rowAddPlace").prev().html() + '</div>';
			var newObject = jQuery(example).insertBefore("#rowAddPlace" );

			jQuery(newObject).find("input").each(function () {
				var newCount = jQuery(this).attr("data-count")*1 + 1;
				var newName = jQuery(this).attr("data-basename") + "#" + newCount;
				jQuery(this).val("");
				jQuery(this).attr("value", "");
				jQuery(this).attr("name", newName);
				jQuery(this).attr("data-count", newCount);

				if (jQuery(this).attr("placeholder") == "_ ___,__") {
					jQuery(this).mask("# ##0,00", {
						reverse: true
					});
				}
			});

			return false;
		});

		jQuery("#rowDelete").click(function(){
			var newCount = jQuery("#cloneTotal").val()*1 - 1;
			jQuery("#cloneTotal").val(newCount);

			jQuery("#rowAddPlace").prev().remove();
			return false;
		});






		///* {{-- Заполнение поля слева, и появление текста справа --}} */
		jQuery(".field_input_fill, .field_select_fill").bind("change keyup input click", function() {
			var val = this.value;
			var name = jQuery(this).attr('name');
			var target = jQuery('.field_' + name);
			if (val != "") {
				target.html(val);
				target.addClass('field_active');
			} else {
				target.html('НЕЗАПОЛНЕННОЕ&nbsp;ПОЛЕ');
				target.removeClass('field_active')
			}
		});

		///* {{-- Чек всех полей слева и автоматичекое внесение справа, если они заполнены --}} */
		jQuery('.field_input_fill, .field_select_fill').each(function() {
			var val = this.value;
			var name = jQuery(this).attr('name');
			var target = jQuery('.field_' + name);
			if (val != "") {
				target.html(val);
				target.addClass('field_active');
			}
		});




	});
});



/* {{-- Эти функции используются в редких настройках полей, когда в самом поле прописывается JS-скрипт для подсчета например зависимых полей или полей перемножаемых друг на друга и т.п. Данные функции позволяют перевести цифры со значениями типа 000 000,00 в реальные числа, или наборот число разместить в поле с использованием маски вида 000 000,00 --}} */
		function DigitFromName(name){
			return Number(jQuery( "input[name$='" + name + "']" ).val().replace(",",".").replace(/\s+/g, ""));
		}
		function DigitToName(name, summ){
			summ = (summ).toLocaleString('ru-RU', {minimumFractionDigits: 2});
			jQuery( "input[name$='" + name + "']" ).val(summ);
		}

/* {{-- А теперь перебор всех полей в которых есть специальный JS-скрипты, и вставка его сюда --}} */
@foreach ($fields as $field)
@if ($field->javascript)
{!! $field->javascript !!}
@endif
@endforeach

/* {{-- 
Примеры таких оформлений расчетов, пока оставлю это тут

jQuery( "input[name$='DOC_SUMM3']" ).prop("disabled", true);
jQuery( "input[name$='DOC_SUMM'], input[name$='DOC_SUMM2']" ).on('input keyup', function(e) {
	var summ = DigitFromName("DOC_SUMM") - DigitFromName("DOC_SUMM2");
	DigitToName("DOC_SUMM3", summ);
});

jQuery( "input[name$='SPEC2_PRICETOT']" ).prop("disabled", true);
jQuery( "input[name$='SPEC2_OBJEM'], input[name$='SPEC2_PRICEM2']" ).on('input keyup', function(e) {
	var summ = DigitFromName("SPEC2_OBJEM") * DigitFromName("SPEC2_PRICEM2");
	DigitToName("SPEC2_PRICETOT", summ);
});

--}} */





</script>
@endpush
