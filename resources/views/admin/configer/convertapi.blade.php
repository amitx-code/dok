@section('title', 'Лимиты сервиса ConvertApi')
@extends('admin.template')

@section('main')

<div class="page-content-wrapper">
	<div class="page-content">
                    <div class="alert-area"></div>


            <div class="row">
                <div class="col-xs-12">
                    <h3 class="page-title">Лимиты сервиса ConvertApi</h3>
                    <div class="img-glyph"><span class="icon-question"></span></div>
				<p style="margin-top:0px;">Сервис <a href="https://www.convertapi.com" target="_blank">ConvertApi</a> предоставляет услуги по конвертации файлов Microsoft Word в формат PDF. Эта услуга платная, и оплата происодит за каждый PDF-файл. Вам необходимо <a href="https://www.convertapi.com/a/si" target="_blank">следить за балансом ConvertApi</a>, потому что как только он закончится, конвертация PDF не будет осуществляться.</p>
                </div>
            </div>



		<div class="row">









                <div class="col-xs-12">
                    <div class="portlet light">
                        <div class="portlet-body form">

                                <div class="row">
                                    <div class="col-xs-12 text-center">Остаток количества секунд для использования ConvertApi</div>
                                    <div class="col-xs-12 text-center">
							<b><span style="font-size:64px;" class="font-green-seagreen">{{ $SecondsLeft }}</span></b>
                                    </div>
                                </div>

					  <br />
                                <div class="row">
                                    <div class="col-xs-12 text-center">Примерное количество PDF-документов, на которых их хватит</div>
                                    <div class="col-xs-12 text-center">
							<b><span style="font-size:64px;" class="font-red-thunderbird">{{ ceil($SecondsLeft / 4) }}</span></b>
                                    </div>
                                </div>

					  <br />
                                <div class="row text-center">
                                    <a href="https://www.convertapi.com/a/si" class="btn btn-primary" target="_blank"><i class="icon-arrow-right"></i>&nbsp;&nbsp;ПЕРЕЙТИ НА САЙТ ConvertApi</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




		</div>



	</div>
</div>
@endsection
