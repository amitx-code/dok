@section('title', 'Мои карты')
@extends('admin.template')

@section('main')




<style>
div.card1 {
	background-position:center center;
	background-repeat:no-repeat;
	background-size:cover;
	width:400px;
	height:251px;
	margin:0 auto 20px auto;
}
div.card1 > div {
	text-align:center;
	color:#dddddd;
	font-weight:900;
	font-size:20px;
	letter-spacing: 4px;
	padding-top:200px;
	}
div.card2 {
	background-position:center center;
	background-repeat:no-repeat;
	background-size:cover;
	width:400px;
	height:251px;
	margin:0 auto 20px auto;
}
div.card2 > div {
	text-align:left;
	}
div.card2 div.code1 {
	float:left;
	margin-top:93px;
	margin-left:44px;
	width:100px;
	}
div.card2 div.code1 svg {
	width:100px;
	height:100px;
}
div.card2 div.code2 {
	float:left;
	margin-top:172px;
	width:95px;
	text-align:center;
	}
div.card2 div.code3 {
	float:left;
	margin-top:142px;
	margin-left:9px;
	width:100px;
	}
div.card2 div.code3 svg {
	width:100px;
	height:50px;
}
input.cardNumber {
	text-align:center;
}
.card3 {
	width:400px;
	height:251px;
}


@media (max-width: 468px) {
div.card1 {
	background-position:center center;
	background-repeat:no-repeat;
	background-size:cover;
	width:300px;
	height:188px;
	margin:0 auto 20px auto;
}
div.card1 > div {
	text-align:center;
	color:#dddddd;
	font-weight:900;
	font-size:16px;
	letter-spacing: 4px;
	padding-top:155px;
	}
div.card2 {
	background-position:center center;
	background-repeat:no-repeat;
	background-size:cover;
	width:300px;
	height:188px;
	margin:0 auto 20px auto;
}
div.card2 > div {
	text-align:left;
	}
div.card2 div.code1 {
	float:left;
	margin-top:70px;
	margin-left:32px;
	width:76px;
	}
div.card2 div.code1 svg {
	width:75px;
	height:75px;
}
div.card2 div.code2 {
	float:left;
	margin-top:126px;
	width:70px;
	text-align:center;
	}
div.card2 div.code3 {
	float:left;
	margin-top:106px;
	margin-left:6px;
	width:100px;
	}
div.card2 div.code3 svg {
	width:75px;
	height:38px;
}
.card3 {
	width:300px;
	height:188px;
}

}


</style>






<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>




                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-credit-card font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Мои карты</span>
                            </div>
                        </div>
                        <div class="portlet-body">




					<div class="row">
						<div class="col-md-6">
						
							<div class="card1" style="background-image: url(/storage/cards/{{ $tariffs[ $user->tariff ]['prefix'] }}_front.png)";>
								<div style="color:{{ $tariffs[ $user->tariff ]['color1'] }}">{{ $user->num_card }}</div>
							</div>
						
						</div>
						<div class="col-md-6">

							<div class="card2" style="background-image: url(/storage/cards/{{ $tariffs[ $user->tariff ]['prefix'] }}_back.png)";>
								<div class="code1"><?PHP
								use App\Models\Barcode;
								$generator = new Barcode();
								$param = $user->num_qrcode;
								$options = Array();
								$options['w'] = 100;
								$options['h'] = 100;
								$options['wq'] = 0;
								$result = $generator->output_image("svg", "qr", $param, $options);
								echo $result;
								?></div>
								<div class="code2">000</div>
								<div class="code3"><?PHP
								$generator = new Barcode();
								$param = $user->num_barcode;
								$options = Array();
								$options['w'] = 100;
								$options['h'] = 50;
								$options['wq'] = 0;
								$result = $generator->output_image("svg", "ean13", $param, $options);
								echo $result;
								?></div>
							</div>

						</div>
					</div>





                        </div><!-- portlet-body -->
                    </div><!-- portlet light -->








	<div class="row">
		<div class="col-md-6">


                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-credit-card font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Привязать/активировать новую карту</span>
                            </div>
                        </div>
                        <div class="portlet-body form">

                            <form action="/admin/billing/cards" id="formNewCard" class="form-horizontal" name="formNewCard" method="post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <div class="col-xs-3">
                                        	<input class="form-control cardNumber" name="card1" maxlength="4" required="required" />
                                    </div>
                                    <div class="col-xs-3">
                                        	<input class="form-control cardNumber" name="card2" maxlength="4" required="required"  />
                                    </div>
                                    <div class="col-xs-3">
                                        	<input class="form-control cardNumber" name="card3" maxlength="4" required="required"  />
                                    </div>
                                    <div class="col-xs-3">
                                        	<input class="form-control cardNumber" name="card4" maxlength="4" required="required"  />
                                    </div>
                                </div>

                                <br />
					  <div class="form-group">
                                    <label class="col-xs-6 control-label">CVN-код на обороте карты&nbsp;<span class="font-red-mint">*</span></label>
                                    <div class="col-xs-6">
                                        	<input class="form-control cvnNumber" rows="3" name="cvn" required="required"  />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ОТПРАВИТЬ</button>
                                </div>
					</form>

                        </div><!-- portlet-body -->
                    </div><!-- portlet light -->

		</div>
		<div class="col-md-6">


                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-credit-card font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Получить пластиковую карту</span>
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

                                <div class="form-group">
                                    <label class="col-md-5 control-label">Получатель</label>
                                    <div class="col-md-7">
                                           <p class="form-control-static"> {{ $user->name_family }} {{ $user->name }} {{ $user->name_patronymic }}</p>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;СОХРАНИТЬ&nbsp;АДРЕС</button>
                                </div>
                            </form>



                        </div><!-- portlet-body -->
                    </div><!-- portlet light -->

		</div>
	</div>










@if ($tariffs[ $user->tariff ]['cards_gifts1'] > 0 || $tariffs[ $user->tariff ]['cards_gifts2'] > 0)
	<div class="alert-area-giftcard-success"></div>
	<div class="row" id="divGiftCard">
		<div class="col-xs-12">

                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-credit-card font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Подарочные карты для твоей команды и твоих друзей</span>
                            </div>
                        </div>
                        <div class="portlet-body form">

					<div class="row">


						<!--{{-- ################################################## --}}-->
						@if ($tariffs[ $user->tariff ]['cards_gifts1'] > 0)
						<div class="col-md-6">
                            			<form action="/admin/billing/post-giftcard" class="formGiftCard form-horizontal" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="tariff" value="GOLD">
								<input type="hidden" name="gift" value="1">

							<div class="row text-center">
								<img src="/storage/cards/GOLD_front.png" alt="" class="card3 center-block">
								<h3>
									У тебя 
									{{ Helpers::VerbalDigit($gifts->cards_gifts1_left, "осталась", "остались", "осталось", false) }}
									<b>{{ $gifts->cards_gifts1_left }}</b>
									{{ Helpers::VerbalDigit($gifts->cards_gifts1_left, "карта", "карты", "карт", false) }}
								</h3>
								<b>{!! $tariffs['GOLD']['emblem'] !!}</b>
							</div>

							@if ($tariffs[ $user->tariff ]['cards_gifts1'] > 0) 
							<br /><br />
							<div class="form-group">
								<label class="col-xs-12 text-center">E-mail получателя&nbsp;<span class="font-red-mint">*</span></label>
								<div class="col-sm-2 col-md-3"></div>
								<div class="col-xs-12 col-sm-8 col-md-6">
								    <div class="input-icon">
									  <i class="icon-envelope"></i>
									<input class="form-control" rows="3" name="email" />
								    </div>
								</div>
							</div>
							<div class="row text-center clearfix">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ПОДАРИТЬ</button>
							</div>
							@endif

							<br /><div class="alert-area-giftcard1"></div>
							</form>
						</div>
						<div class="clearfix visible-xs visible-sm"><br /><br /></div>
						@endif
						<!--{{-- ################################################## --}}-->


						<!--{{-- ################################################## --}}-->
						@if ($tariffs[ $user->tariff ]['cards_gifts1'] > 0)
						<div class="col-md-6">
                            			<form action="/admin/billing/post-giftcard" class="formGiftCard form-horizontal" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="tariff" value="BUSINESS START">
								<input type="hidden" name="gift" value="2">

							<div class="row text-center">
								<img src="/storage/cards/BST_front.png" alt="" class="card3 center-block">
								<h3>
									У тебя 
									{{ Helpers::VerbalDigit($gifts->cards_gifts2_left, "осталась", "остались", "осталось", false) }}
									<b>{{ $gifts->cards_gifts2_left }}</b>
									{{ Helpers::VerbalDigit($gifts->cards_gifts2_left, "карта", "карты", "карт", false) }}
								</h3>
								<b>{!! $tariffs['BUSINESS START']['emblem'] !!}</b>
							</div>

							@if ($tariffs[ $user->tariff ]['cards_gifts2'] > 0) 
							<br /><br />
							<div class="form-group">
								<label class="col-xs-12 text-center">E-mail получателя&nbsp;<span class="font-red-mint">*</span></label>
								<div class="col-sm-2 col-md-3"></div>
								<div class="col-xs-12 col-sm-8 col-md-6">
								    <div class="input-icon">
									  <i class="icon-envelope"></i>
									<input class="form-control" rows="3" name="email" />
								    </div>
								</div>
							</div>
							<div class="row text-center clearfix">
								<button type="submit" class="btn btn-primary"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ПОДАРИТЬ</button>
							</div>
							@endif

							<br /><div class="alert-area-giftcard2"></div>
							</form>
						</div>
						@endif
						<!--{{-- ################################################## --}}-->


					</div>

                        </div><!-- portlet-body -->
                    </div><!-- portlet light -->

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
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {
	jQuery(function() {
		jQuery( "input[name$='zip']" ).mask("000000", {
			placeholder: "000000",
		});
		jQuery( "input.cardNumber" ).mask("0000", {
			placeholder: "0000",
		});
		jQuery( "input.cvnNumber" ).mask("000", {
			placeholder: "000",
		});
	});



	$('.formGiftCard').commonForm((data, $form) => {
		const $alert = $('<div class="custom-alerts alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="glyphicon glyphicon-remove"></i> <span></span></div>');

		if (data.result == 'success') {
			const $alert = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><br><i class="fa-lg fa fa-fa fa-check"></i> <span></span><br><br></div>');
			$alert.find('span').html('Подарочная карта была успешно отправлена, и пользователь уже получил новый тариф! Спасибо!');
			$('.alert-area-giftcard-success').append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);
			$('#divGiftCard').slideUp(500);

		} else if (data.result == 'notfound') {
			$alert.find('span').html('Пользователь с таким E-mail отсутствует на нашем сайте. Пожалуйста проверь, правильно ли был введён E-mail?<br />Или сначала просто пригласи его зарегистрироваться - <a href="/admin/afillater/partnership" class="alert-link">отправь ему свою персональную партнёрскую ссылку и приглашение</a>');
			$(this).find('.alert-area-giftcard' + data.gift).append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);

		} else if (data.result == 'notariff') {
			$alert.find('span').html('У пользователя с таким E-mail и так уже действует тариф с таким же или более высоким уровнем чем тот, который ты хочешь ему сейчас подарить');
			$(this).find('.alert-area-giftcard' + data.gift).append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);

		} else if (data.result == 'myselt') {
			$alert.find('span').html('Нельзя подарить карту самому себе! Указанный адрес E-mail является именно твоим E-mail адресом, зарегистрированным в этом аккаунте');
			$(this).find('.alert-area-giftcard' + data.gift).append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);

		} else if (data.result == 'total') {
			$alert.find('span').html('Лимит подарков данного типа карт у тебя уже исчерпан, ты не можешь подарить их больше, чем предусмотрено твоим тарифом');
			$(this).find('.alert-area-giftcard' + data.gift).append($alert);
			setTimeout(() => $alert.fadeOut(500, () => $alert.remove()), 10000);
		}

	});


});



</script>
@endpush