@section('title', 'Добавить документ')
@extends($template)
@section('main')


<style>
h1 i {
	font-size:92px;
}
#myDropzone {
	border: 2px dashed #028AF4;
	padding:10px;
}
#myDropzone .bg {
	color:#028AF4;
	background-color:#DADFE5;
	padding:70px;
	text-align:center;
	font-size:22px;
	font-weight:300;
}
</style>



<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>
<form action="{{ $urlPrefix }}/admin/purchaser/add" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="api_token" value="{{ $request->api_token }}">


	<div class="row">
		<div class="col-xs-12">
			<div class="portlet light">
				<div class="portlet-body text-center">

					<h1>Как это работает?</h1>
					<br />

					<div class="mt-element-step">
                                            <div class="row step-background">
                                                <div class="col-md-4 bg-grey-steel mt-step-col">
                                                    <div class="mt-step-number">1</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Покупка</div>
                                                    <div class="mt-step-content font-grey-cascade">Совершите покупку у нашего партнёра</div>
                                                </div>
                                                <div class="col-md-4 bg-grey-steel mt-step-col active">
                                                    <div class="mt-step-number">2</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Загрузка</div>
                                                    <div class="mt-step-content font-grey-cascade">Загрузите документ о покупке (например чек)</div>
                                                </div>
                                                <div class="col-md-4 bg-grey-steel mt-step-col error">
                                                    <div class="mt-step-number">3</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Бонусы</div>
                                                    <div class="mt-step-content font-grey-cascade">Получите бонус на свой счёт</div>
                                                </div>
                                            </div>
					</div>


					<br />
					<h1>Загрузить документ о покупке</h1>
					<br />

					<div id="myDropzone">
						<div class="bg">
							<div class="fallback">
								<input type="file" name="file" />
							</div>
							@if (Route::currentRouteName() == "mobile")
								НАЖМИТЕ СЮДА, ЧТОБЫ ВЫБРАТЬ И ЗАГРУЗИТЬ ФАЙЛ
							@else
								ПЕРЕТАЩИТЕ МЫШКОЙ ФАЙЛ В ЭТУ ЗОНУ<br /><br />
								или просто нажмите сюда, чтобы выбрать и загрузить файл
							@endif
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="row mybuttons">
		<div class="col-xs-12 text-center">
			<button class="btn btn-lg btn-lgg btn-default">
				<i class="icon-paper-plane"></i> &nbsp; ЗАГРУЗИТЬ</button>
			<br /><br />
		</div>
	</div>


</form>
</div>
</div>
@endsection
@push('scripts')
<script src="/js/js.dropzone.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready( function () {
	jQuery("#myDropzone").dropzone({ url: "{{ $urlPrefix }}/admin/purchaser/add{{ $urlAfter }}" });
});
</script>
@endpush
