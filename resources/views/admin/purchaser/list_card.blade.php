@section('title', 'Карточка документа')
@extends($template)
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

.list-group h4 {
	margin:0px;
}
</style>



<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>






@if (($user->id == $document->user_id) || ($user->role_id > $document->user_role_id))
	<div class="row">
		<div class="col-xs-12">
                    <h1 class="page-title">
			  	{{ $document->name }}
				@if ($document->number) 		№ {{ $document->number }} @endif
				@if ($document->date) 			от {{ Helpers::Date($document->date, true) }} года @endif
			   </h1>
		</div>
	</div>





	<div class="row">




		<div class="col-xs-12 col-sm-6">




			<ul class="list-group">
@if ($document->checked == "completed")
				<li class="list-group-item bg-yellow-soft">
					<b><i class="fa fa-undo text-gray"></i> 
					Кэшбек подтверждён</b>
					<span><h4><b>5%</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@if ($document->checked_comment)
				<li class="list-group-item bg-yellow-soft">
					Комментарии
					<span>{{ $document->checked_comment }}</span>
					<div style="clear:both;"></div>
				</li>
				@endif
@elseif ($document->checked == "denied")
				<li class="list-group-item bg-red-thunderbird font-white">
					<b><i class="fa fa-undo text-gray"></i> 
					В кэшбеке отказано</b>
					<span><h4><b>5%</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@if ($document->checked_comment)
				<li class="list-group-item bg-red-thunderbird font-white">
					Причина отказа
					<span>{{ $document->checked_comment }}</span>
					<div style="clear:both;"></div>
				</li>
				@endif
@else
				<li class="list-group-item bg-yellow-saffron">
					<b><i class="fa fa-undo text-gray"></i> 
					Кэшбек на подтверждении</b>
					<span><h4><b>5%</b></h4></span>
					<div style="clear:both;"></div>
				</li>
				@if ($document->checked_comment)
				<li class="list-group-item bg-yellow-saffron">
					Комментарии
					<span>{{ $document->checked_comment }}</span>
					<div style="clear:both;"></div>
				</li>
				@endif
@endif
			</ul>




			<ul class="list-group">

				<li class="list-group-item">
					Название
					<span>{{ $document->name }}</span>
					<div style="clear:both;"></div>
				</li>
@if ($document->number)
				<li class="list-group-item">
					Номер
					<span>{{ $document->number }}</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->date)
				<li class="list-group-item">
					Дата документа
					<span>{{ Helpers::Date($document->date, true) }} года</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->summ)
				<li class="list-group-item">
					Сумма
					<span class="text-nowrap">{{ number_format($document->summ, 2, ',', ' ') }}</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->client)
				<li class="list-group-item">
					Партнёр
					<span>{{ $document->client }}</span>
					<div style="clear:both;"></div>
				</li>
@endif

			</ul>




			<ul class="list-group">

				<li class="list-group-item">
					ID документа
					<span>D{{ str_pad($document->id, 7, "0", STR_PAD_LEFT) }}</span>
					<div style="clear:both;"></div>
				</li>
@if ($document->created)
				<li class="list-group-item">
					Дата и время создания
					<span>{{ Helpers::DateTime($document->created, true) }}</span>
					<div style="clear:both;"></div>
				</li>
@endif

			</ul>
		</div>





		<div class="col-xs-12 col-sm-6">

			<div class="text-center">
				@if ($document->filename && Route::currentRouteName() != "mobile")
				<a href="{{ $urlPrefix }}/admin/purchaser/download/{{ $document->id }}/pdf{{ $urlAfter }}" class="btn btn-lg btn-lgg btn-primary btnPause" style="width:100%;">
					<i class="icon-arrow-down"></i> &nbsp; СКАЧАТЬ ФАЙЛ</a>
				<br />
				<br />
				@endif
				<a href="{{ $urlPrefix }}/admin/purchaser/list{{ $urlAfter }}" class="btn btn-lg btn-lgg btn-default" style="width:100%;">
					<i class="icon-action-undo"></i> &nbsp; ВСЕ ДОКУМЕНТЫ</a>
				<br />
				<br />
			</div>

		</div>



	</div>










@else
	<div class="alert-area">
		<div class="custom-alerts alert alert-danger fade in">
			<h1>
				<i class="icon-ban"></i> 
				<span>Отсутствуют права доступа к документу</span>
			</h1>
			<br />
			<p>Этот документ принадлежит другому пользователю, и у вас нет к нему доступа</p>
			<br />
		</div>
	</div>
@endif





</div>
</div>
@endsection
@push('scripts')
<script>
jQuery(".btnPause").click(function(){
	if ( jQuery(this).attr( "disabled" ) != "disabled" ) {
		jQuery(this).find("i").addClass("icon-refresh");
		jQuery(this).attr( "disabled", "disabled" );
	} else {
		return false;
	}
});
</script>
@endpush
