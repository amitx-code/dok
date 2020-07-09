@section('title', 'Услуги')
@extends('admin.template')

@section('main')
<style>
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem;
}
.card-title {
    margin-bottom: .75rem;
    text-align:left;
}
.card-img-top {
    width: 100%;
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
}
</style>
<link rel="stylesheet" type="text/css" href="/css/public_typography.css" />
<link rel="stylesheet" type="text/css" href="/css/public_colors.css" />









<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>






	<div class="row">

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-30">
			<div class="card">
				<a href="/admin/services/1" style="height:100%;">
					<img class="card-img-top" src="/img/services/01.jpg" alt=""></a>
				<div class="card-body">
					<h5 class="card-title">Пакет кобрэндинговых карт со стандартным дизайном</h5>
					<a href="/admin/services/1" class="btn  btn-primary">Заказать</a>
				</div>
			</div>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-30">
			<div class="card">
				<a href="/admin/services/2" style="height:100%;">
					<img class="card-img-top" src="/img/services/03.jpg" alt=""></a>
				<div class="card-body">
					<h5 class="card-title">Пакет кобрэндинговых карт с индивидуальным дизайном</h5>
					<a href="/admin/services/2" class="btn  btn-primary">Заказать</a>
				</div>
			</div>
		</div>

		<div class="clearfix visible-sm"></div>

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-30">
			<div class="card">
				<a href="/admin/services/3" style="height:100%;">
					<img class="card-img-top" src="/img/services/02.jpg" alt=""></a>
				<div class="card-body">
					<h5 class="card-title">До-выпуск карт с разработанным дизайном</h5>
					<a href="/admin/services/3" class="btn  btn-primary">Заказать</a>
				</div>
			</div>
		</div>

		<div class="clearfix visible-lg visible-md"></div>

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-30">
			<div class="card">
				<a href="/admin/services/4" style="height:100%;">
					<img class="card-img-top" src="/img/services/04.jpg" alt=""></a>
				<div class="card-body">
					<h5 class="card-title">Приоритетное размещение в категории на сайте</h5>
					<a href="/admin/services/4" class="btn  btn-primary">Заказать</a>
				</div>
			</div>
		</div>

		<div class="clearfix visible-sm"></div>

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-30">
			<div class="card">
				<a href="/admin/services/5" style="height:100%;">
					<img class="card-img-top" src="/img/services/05.jpg" alt=""></a>
				<div class="card-body">
					<h5 class="card-title">Заказать рекламу на баннере сайта</h5>
					<a href="/admin/services/5" class="btn  btn-primary">Заказать</a>
				</div>
			</div>
		</div>

	</div>




</div>
</div>
@endsection
