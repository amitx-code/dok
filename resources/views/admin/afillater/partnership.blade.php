@section('title', 'Партнёрская программа')
@extends('admin.template')

@section('main')



    <div class="page-content-wrapper">
        <div class="page-content">



            <div class="row">
                <div class="col-sm-12">
                    <div class="portlet light note note-danger" id="for-service">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-link font-red-mint"></i> 
                                <span class="caption-subject font-red-mint sbold uppercase">Получай до 10% ОТ ПРИГЛАШЁННЫХ ТОБОЙ ПОЛЬЗОВАТЕЛЕЙ</span>
                            </div>
                        </div>
                        <div class="portlet-body">                    
					Наша миссия: Предоставить каждому возможность полностью реализовать свой потенциал.  
					Каждый человек имеет право быть успешным, и&nbsp;мы хотим, чтобы таких людей было больше.
					Мы с&nbsp;радостью отдадим тебе до 10%&nbsp;дохода, если ты просто поделишься персональной 
					ссылкой в&nbsp;социальных сетях, мессенджерах, группах и&nbsp;т.&nbsp;д. Давай вместе сделаем мир лучше.
                        </div><!-- portlet-body -->
                    </div>
                </div>
            </div>
                    






<!--{{-- СТАРЫЙ БЛОК УРОВЕНЕЙ АФИЛЛАТОВ, НАПИСАННЫЙ ЕЩЁ ДО ГИВИ, СОХРАНИТЬ ПОКА, ВДРУГ ПРИГОДИТСЯ
            <div class="row">
@php ($level = 1)
@foreach ($refAll as $ref)
                <div class="col-md-4">
				    	   
                                <span class="caption-subject font-dark sbold uppercase">{{ $level ++ }} уровень зарегистрировались</span>
					  <br />

                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-green icon-users"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-body-stat" data-counter="counterup">{{ count($ref) }}</span>
                                        <span class="widget-thumb-subtitle">
						    		{{ Helpers::VerbalDigit(count($ref), "партнёр", "партнёра", "партнёров", false) }}
						    </span>

							<small style="text-transform:capitalize;">
							@foreach ($ref as $usr)
							{{ $usr }}@if (!$loop->last), @endif
							@endforeach
							</small>

                                    </div>
                                </div>
                            </div>
                </div>
@endforeach
            </div>
--}}-->







@include('admin.afillater.afilliate_matrix')








            <div class="row">
                <div class="col-sm-12">
                    <div class="portlet light note note-success" id="for-service">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-trophy font-dark"></i> 
                                <span class="caption-subject font-dark sbold uppercase">Твоя персональная партнерская ссылка</span>
                            </div>
                        </div>
                        <div class="portlet-body">                    


					<div class="text-center">
						<span class="personal-link">{{ Config('global.project.url') }}/?ref{{ $referal }}</span><br />

						<div class="text-center">
							<?PHP
							use App\Models\Barcode;
							$generator = new Barcode();
							$param = Config('global.project.url') . "/?ref" . $referal;
							$options = Array();
							$options['w'] = 250;
							$options['h'] = 250;
							$options['wq'] = 0;
							$result = $generator->output_image("svg", "qr", $param, $options);
							echo $result;
							?>
						</div>

						<br /><br />

						<style>
						.ya-share2__container_size_m .ya-share2__icon {height: 48px; width: 48px;}
						.ya-share2__item_service_moimir .ya-share2__badge {margin-bottom: 4px;}
						</style>
						<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" async="async"></script>
						<script src="//yastatic.net/share2/share.js" async="async"></script>
						<h2>Поделиться ссылкой:</h2>
						<div style="margin-top:-25px;">
							<div class="ya-share2" 
							data-services="collections,vkontakte,facebook,odnoklassniki,moimir,pinterest,twitter,blogger,delicious,digg,reddit,evernote,linkedin,lj,viber,whatsapp,skype,telegram"
							data-image="https://getlaw24.ru/img_public/ico/02.png" 
							data-url="{{ Config('global.project.url') }}/?ref{{ $referal }}"
							data-title="Отличный кэшбэк-сервис Vista. Больше чем деньги." 
							data-description="Регистрируйся бесплатно:" 
							data-lang="ru" 
							data-size="m"
							></div>
						</div>

					</div>


                      		<div>
								Скопируй ссылку, и&nbsp;отправь приятелю. 
                     Или даже просто опубликуй её в&nbsp;блоге или в&nbsp;соцсети&nbsp;- пусть заходят все! 
                     Чем больше людей ее увидят и&nbsp;кликнут, тем больше будет твой доход.
                                Запрещается рассылать СПАМ, публиковать ссылку на "дорвей"-сайтах, давайть рекламу на&nbspбрэнд, применять Adult-площадки, и&nbsp;использовать другие нелегальные или полу-легальные способы продвижения. Не забывай о&nbsp;том, что мы всегда проверяем любые выплаты - в&nbsp;том числе и&nbsp;источники их получения. 
					</div>

                   		<div>
                                * мы можем вручную задать для тебя персональную "красивую" ссылку типа  
                                <B>https://getlaw24.ru/?ref<span class="font-red-soft">XXXXXXXXXXX</span></B> 
                                где в конце будет не набор цифр, а внятное слово или цифры. 
                                Для этого <a href="/admin/support/service">
                                напиши нам</A> свой логин и желаемое имя для ссылки.
					</div>

                        </div><!-- portlet-body -->
                    </div>
                </div>
            </div>
                    



        </div>
    </div>
@endsection