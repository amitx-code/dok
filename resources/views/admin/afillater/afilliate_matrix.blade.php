<link rel="stylesheet" type="text/css" href="/css/public_colors.css" />
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	vertical-align: middle;
	width:11%;
}
.table>tbody>tr>td, .table>tfoot>tr>td, .table>thead>tr>td {
	color:#BBBBBB;
}
.table>thead>tr>th {
}
.table {
	border:1px solid #666666;
	border-left:5px solid #AE89F1;
}
.table div.b {
	font-weight:bold;	
	font-size:150%;
	margin-top:-5px;
	margin-bottom:-5px;
	color:#000000;
}
.table div.b a {
	display:block;
	color:#000000;
	border:1px solid #999999;
	border-radius:4px!important;
	margin:0px 3px;
}
.table div.b a:hover {
	background-color: #AE89F1;
	color:#FFFFFF;
	text-decoration:none;
}
.table>tbody>tr.total>td {
	background-color: #666666;
	color:#FFFFFF;
}
.table>tbody>tr.total>td div.b {
	font-weight:bold;	
	font-size:150%;
	margin-top:-5px;
	margin-bottom:-5px;
	color:#FFFFFF;
}

@media only screen and (max-width: 768px) {
.flip-scroll .flip-content tbody tr td {
	text-align:center;
	width: auto;
}
}

.table tr.noactive td {
	background-color:#dddddd;
	color:#999999;
	border-top: 1px solid #eeeeee;
}
.table tr.noactive td div.b {
	color:#999999;
}
</style>




            <div class="row">
                <div class="col-md-12">
		    <div class="portlet light bordered">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-cursor font-dark hide"></i>
                                            <span class="caption-subject font-dark bold uppercase">
							  	<i class="icon-speedometer"></i> Результаты твоей партнёрской сети
							</span>
                                        </div>
                                    </div>

		    <div class="portlet-body">



			<div class="flip-scroll">
				<table class="table table-striped table-condensed flip-content">
					<thead class="flip-content">
						<tr >
							<th class="text-center">Уровень</th>
							<th class="text-center">Люди</th>
							@foreach (config('tariffs') as $tarifCode => $tarif) 
							<th class="text-center">
								  {!! $tarif['logo'] !!}
							</th>
							@endforeach
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>

						@php $count=1; @endphp
						@foreach ($matrix->levels as $level)
						<tr class="
								@php $active=false; @endphp
								@if ($count>=2 && $count<=3 && $tariffs[$user->tariff]['level']<3)
								noactive
								@elseif ($count>=4 && $count<=5 && $tariffs[$user->tariff]['level']<4)
								noactive
								@elseif ($count>=6 && $count<=8 && $tariffs[$user->tariff]['level']<5)
								noactive
								@else
								@php $active=true; @endphp
								active
								@endif
						">
							<td class="text-center"><div class="b">{{ $level->level }}</div></td>
							<td class="text-center">
								@if ($level->total > 0)<div class="b">@endif
								{{ $level->total }}
								@if ($level->total > 0)</div>@endif
							</td>
							@foreach ($level->tariffs as $tarif)
							<td class="text-center">
								@if ($tarif->count)
								<div class="b">
									@if ($active==true)<a href="{{ $tarif->url }}">@endif
									{{ $tarif->count }}</a>
								</div>
								@else
								{{ $tarif->count }}
								@endif
							</td>
							@endforeach
							<td class="text-center">

								@if ($count>=2 && $count<=3 && $tariffs[$user->tariff]['level']<3)
								<a href="/admin/billing/order?tariff=BUSINESS%20START" class="btn" style="background-color:#B9B5E0; color:#000000; padding: 0px 6px;">Активировать</a>
								@elseif ($count>=4 && $count<=5 && $tariffs[$user->tariff]['level']<4)
								<a href="/admin/billing/order?tariff=BUSINESS%20OPTIMUM" class="btn" style="background-color:#2FCA9B; color:#ffffff; padding: 0px 6px;">Активировать</a>
								@elseif ($count>=6 && $count<=8 && $tariffs[$user->tariff]['level']<5)
								<a href="/admin/billing/order?tariff=BUSINESS%20MAX" class="btn" style="background-color:#2BB2EB; color:#ffffff; padding: 0px 6px;">Активировать</a>
								@else
								<span class="font-green-jungle">Активен</span>
								@endif

							</td>
						</tr>
						@php $count++; @endphp
						@endforeach
			
						<tr class="total">
							<td class="text-center">ИТОГО</td>
							<td class="text-center"><div class="b">{{ $matrix->total }}</div></td>
							@foreach ($matrix->tariffs as $tarif)
							<td class="text-center"><div class="b">{{ $tarif->count }}</div></td>
							@endforeach
							<td>&nbsp;</td>
						</tr>
			
					</tbody>
				</table>
			</div>



                </div>
                </div>
                </div>
             </div>




<!--{{--
@foreach ($matrix->levels as $level)
    <div class="row">
        <div class="col-sm-1">{{ $level->level}} уровень</div>
        <div class="col-sm-1">total: {{ $level->total }}</div>
        @foreach ($level->tariffs as $tarif)
            <div class="col-sm-1">
                @if ($tarif->count)
                    <a href="{{ $tarif->url }}">{{ $tarif->prefix }} {{ $tarif->count }}</a>
                @else
                    {{ $tarif->prefix }} {{ $tarif->count }}
                @endif
            </div>
        @endforeach
        <div class="col-sm-1">
            @if ($level->isActive)
                активен
            @else
                активировать
            @endif
        </div>
    </div>
@endforeach

<div class="row">
    <div class="col-sm-1">Итого</div>
    <div class="col-sm-1">total: {{ $matrix->total }}</div>
    @foreach ($matrix->tariffs as $tarif)
        <div class="col-sm-1">{{ $tarif->prefix }} {{ $tarif->count }}</div>
    @endforeach
    <div class="col-sm-1">
    </div>
</div>
--}}-->
