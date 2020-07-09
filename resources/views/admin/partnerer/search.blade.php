@section('title', 'Поиск партнёра')
@extends('admin.template')

@section('main')


<style>
.superbig {
	margin:30px 0;
	font-size:36px;
	font-weight: 300;
	font-family: Open Sans,sans-serif;
	padding:15px;
	text-align:center;
	width:100%;
}
h1 i {
	font-size:92px;
}
</style>



<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>
<form action="/admin/partnerer/list" method="get">


	<div class="row">
		<div class="col-xs-12">
			<div class="portlet light">
				<div class="portlet-body">
					<br />
					<h1 class=" text-center">
						<i class="icon-magnifier"></i><br /><br />
						НАЙТИ ПАРТНЁРА
					</h1>
					<div class="row  text-center">
						<div class="col-xs-12 col-sm-3">&nbsp;</div>
						<div class="col-xs-12 col-sm-6">
							<input type="text" name="text" class="superbig" placeholder="Что ищем?" />
						</div>
						<div class="col-xs-12 col-sm-3">&nbsp;</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<div class="mt-checkbox-list" style="width:180px; margin:0 auto;">
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input type="checkbox" name="now" value="on"> Работает сейчас
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-checkbox mt-checkbox-outline mt-checkbox-disabled">
                                                                <input type="checkbox" name="near" value="on"> Находится рядом
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input type="checkbox" name="online" value="on"> Есть услуги онлайн
                                                                <span></span>
                                                            </label>
							</div>
							<br />
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>




	<div class="row mybuttons">
		<div class="col-xs-12 text-center">
			<button class="btn btn-lg btn-lgg btn-primary">
				<i class="icon-magnifier"></i> &nbsp; ИСКАТЬ</button>
			<br /><br />
		</div>
	</div>


</form>
</div>
</div>
@endsection
@push('scripts')
<script>
</script>
@endpush
