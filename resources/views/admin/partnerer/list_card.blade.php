@section('title', 'Подробно о партнёре')
@extends('admin.template')

@section('main')

<style>
.list-group-item>span {
    float: right;
}
.badge {
    font-size: 14px!important;
	height:auto;
	padding-left:15px;
	padding-right:15px;
	margin-top:-2px;
	margin-bottom:-2px;
	}
.badge-default {
	color:#000000;
	}


.mytitle {
	padding:30px;
	background-position:center center;
	background-size:cover;
}
.mytitle h1 {
	font-size:48px;
}
.mytitle h1,
.mytitle h2,
.mytitle h3,
.mytitle b {
	color:#ffffff;
}
.mytitle img {
	float:right;
	width:160px;
	height:160px;
	border-radius:80px !important;
	margin:0 0 20px 20px;
}
.mytitle a {
	text-decoration:none;
}

.mybuttons a {
	width:100%;
	text-transform:uppercase;
	margin-bottom:12px;
}
.mybuttons a.mycolor {
	width:100%; 
	background-image: linear-gradient(63deg,rgba(0,0,0,.3),rgba(255,255,255,0));
	background-color:#{{ $partner->color }};
	color:#ffffff;
}
.mybuttons a.partnerlink {
	display:table-cell;
	vertical-align:middle;
	height: 83px;
	width:1000px;
}

.list-group h4 {
	margin:0px;
}
</style>



<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>





	<div class="row">
		<div class="col-xs-12">
			<div class="mytitle" style="background-image:url('{{ $partner->filename_bg }}') !important;">
				@if ($partner->url_www && $partner->url_partnerlink)<a href="{{ $partner->url_partnerlink }}" target="_blank">@endif
				   <img src="{{ $partner->filename_logo }}" alt="">
				   <h3><b>{{ $partner->name }}</b></h3>
				   <h1>{{ $partner->title }}</h1>
				   <!--{{--<h2>Кэшбек <b>{{ $partner->cashback }}</b> {{ $partner->type }}</h2>--}}-->
				 @if ($partner->url_www && $partner->url_partnerlink)</a>@endif
			 </div>
		</div>
	</div>
	<br />





	<div class="row mybuttons">
		<div class="col-xs-12 col-sm-6">

			<ul class="list-group">
				@if ($partner->cashback)
				<li class="list-group-item">
					<i class="fa fa-undo text-gray"></i> 
					Кэшбэк или вознаграждение
					<span><h4><b>
						@if ($partner->cashback_to=="Y") до @endif
						{{ $partner->cashback }} 
						<span class="text-gray">{{ $partner->cashback_type }}</span>
					</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@endif
				@if ($partner->discount)
				<li class="list-group-item">
					<i class="fa fa-tags text-gray"></i> 
					Скидка
					<span><h4><b>
						@if ($partner->discount_to=="Y") до @endif
						{{ $partner->discount }} 
						<span class="text-gray">{{ $partner->discount_type }}</span>
					</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@endif
				@if ($partner->cashback_time)
				<li class="list-group-item">
					<i class="fa fa-history text-gray"></i> 
					Время ожидания
					<span><h4><b>{{ $partner->cashback_time }}</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@else
				<li class="list-group-item">
					<i class="fa fa-list text-gray"></i> 
					Категория
					<span><h4><b>{{ $partner->category_name }}</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@endif
			</ul>

		</div>
		@if ($partner->url_partnerlink)
		<div class="col-xs-12 col-sm-6 text-center">
			<a href="{{ $partner->url_partnerlink }}" target="_blank" class="btn btn-lg btn-lgg btn-default mycolor partnerlink">
				<i class="fa fa-gem"></i> &nbsp; К ПОКУПКАМ У ПАРТНЁРА</a><br />
		</div>
		@endif
	</div>




	<div class="row">
		<div class="col-xs-12">
			<div class="portlet light">
				<div class="portlet-body">
					{!! $partner->description !!}
				</div>
			</div>
		</div>
	</div>





	<div class="row">

		<div class="col-xs-12 col-sm-6">
			<ul class="list-group">
				@if ($partner->url_www && $partner->url_partnerlink)
				<li class="list-group-item">
					<a href="{{ $partner->url_partnerlink }}" target="_blank" data-toggle="tooltip" data-placement="left" data-original-title="Посетить веб-сайт">{{ $partner->url_www }}</a>
					<span><a href="{{ $partner->url_partnerlink }}" target="_blank" data-toggle="tooltip" data-placement="left" data-original-title="Посетить веб-сайт"><i class="fa fa-link"></i></a></span>
					<div style="clear:both;"></div>
				</li>
				@endif
				@if ($partner->addr_post)
				<li class="list-group-item">
					{{ $partner->addr_post }}
					<span><a href='https://yandex.ru/maps/?mode=search&text={{ $partner->addr_post }}' target="_blank" data-toggle="tooltip" data-placement="left" data-original-title="Показать на карте"><i class="fa fa-globe"></i></a></span>
					<div style="clear:both;"></div>
				</li>
				@endif
				@if ($partner->addr_phones)
				<li class="list-group-item">
					{{ $partner->addr_phones }}
					<span><a href='callto:{{ $partner->addr_phones }}' data-toggle="tooltip" data-placement="left" data-original-title="Позвонить прямо сейчас"><i class="fa fa-phone"></i></a></span>
					<div style="clear:both;"></div>
				</li>
				@endif
				@if ($partner->addr_emails)
				<li class="list-group-item">
					{{ $partner->addr_emails }}
					<span><a href='mailto:{ $partner->addr_emails }}'  data-toggle="tooltip" data-placement="left" data-original-title="Написать письмо"><i class="fa fa-envelope"></i></a></span>
					<div style="clear:both;"></div>
				</li>
				@endif
			</ul>
		</div>

		<div class="col-xs-12 col-sm-6 mybuttons">

			@if ($partner->url_partnerlink)
			<a href="{{ $partner->url_partnerlink }}" target="_blank" class="btn btn-lg btn-lgg btn-default mycolor">
				<i class="icon-diamond"></i> &nbsp; К ПОКУПКАМ У ПАРТНЁРА</a>
			@endif
			<a href="/admin/partnerer/list/{{ $partner->category_id }}-{{ $territoryId }}" class="btn btn-lg btn-lgg btn-default">
				<i class="icon-size-actual"></i> &nbsp; ВСЕ В КАТЕГОРИИ «{{ $partner->category_name }}»</a>
			<a href="/admin/partnerer/list/0-{{ $territoryId }}" class="btn btn-lg btn-lgg btn-default">
				<i class="icon-action-undo"></i> &nbsp; ДРУГИЕ ПАРТНЁРЫ</a>

		</div>

	</div>



<!--{{--
	<div class="row mybuttons">
		<div class="col-xs-12 col-sm-6 text-center">
			<a href="/admin/partnerer/list" class="btn btn-lg btn-lgg btn-default">
				<i class="icon-action-undo"></i> &nbsp; ДРУГИЕ ПАРТНЁРЫ</a>
			<br /><br />
		</div>
		<div class="col-xs-12 col-sm-6 text-center">
			<a href="{{ $partner->url_partnerlink }}" target="_blank" class="btn btn-lg btn-lgg btn-default mycolor">
				<i class="icon-pin"></i> &nbsp; КУПИТЬ У ПАРТНЁРА</a>
			<br /><br />
		</div>
	</div>
--}}-->




</div>
</div>
@endsection
@push('scripts')
<script>
</script>
@endpush
