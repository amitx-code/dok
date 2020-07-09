@section('title', 'История рейтинга')
@extends('admin.template')

@section('main')
<link rel="stylesheet" type="text/css" href="/css/public_colors.css" />


<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
table.dataTable thead th, table.dataTable tfoot th {
    font-weight: normal;
	}
table.dataTable thead th.sorting_asc, 
table.dataTable tfoot th.sorting_asc, 
table.dataTable thead th.sorting_desc, 
table.dataTable tfoot th.sorting_desc {
	font-weight:bold;
	}
</style>






    <div class="page-content-wrapper">
        <div class="page-content">








				<div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 red">
                                    <div class="visual">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number" style="font-size:22px; line-height:normal; font-weight:600;">
                                            {!! $rating->logo !!}
                                        </div>
                                        <div class="desc" style="margin-top:10px;"> Текущий рейтинг </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="{{ $rating->total }}">{{ $rating->total }}</span>
                                        </div>
                                        <div class="desc" style="margin-top:10px;"> Сейчас баллов</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 green">
                                    <div class="visual">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="{{ $rating->price_to - $rating->total +0.01 }}">{{ $rating->price_to - $rating->total +0.01 }}</span>
                                        </div>
                                        <div class="desc"> До следующего уровня </div>
                                    </div>
                                </div>
                            </div>

                        </div>








			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered">                           

                      <table class="table table-striped table-advance table-hover dataTable" id="tableBilling">
                        <thead>
                            <tr>
                                <th class="hidden">&nbsp;</th>
                                <th class="hidden-xs">Дата и время</th>
                                <th class="text-right bill">Сумма</th>
                                <th class="">Комментарий</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($history as $row)
                            <tr class="billing-records">
                                <td class="hidden">{{ $row->created }}</td>
                                <td class="hidden-xs"><nobr>{{ Helpers::DateTime($row->created, true, true) }}</nobr></td>
                                <td class="bill {{ $row->sum > 0 ? 'debit' : 'credit' }}"><nobr><B>{{ $row->sum }}</B></nobr></td>
					  <td>{!! $row->description !!}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                        <thead>
                            <tr>
                                <th class="hidden">&nbsp;</th>
                                <th class="hidden-xs">Дата и время</th>
                                <th class="text-right bill">Сумма</th>
                                <th class="">Комментарий</th>
                            </tr>
                        </thead>
                    </table>

                        </div>
                    </div>
                </div>
			</div>










			<a name="settings"></a>
			<div class="row">
				<div class="col-lg-12">
					<div class="portlet light bordered">       

						<div class="portlet-title"><div class="caption">
							<span class="caption-subject font-dark sbold uppercase">Таблица рейтинговых баллов</span>
						</div></div>
	
						<div class="portlet-body">

                      <table class="table table-striped table-advance table-hover dataTable">
                        <thead>
                            <tr>
                                <th class="">Название</th>
                                <th class="text-right">Рейтинговых баллов</th>
                                {{--<th class="text-right">Рейтинговых баллов до</th>--}}
                                <th class="text-right">Выплата бонуса</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($ratings as $row)
                            <tr class="billing-records">
                                <td class="">{!! $row['logo'] !!}</td>
                                <td class="text-right">{{ $row['price_from'] }}</td>
                                {{--<td class="text-right">{{ $row['price_to'] }}</td>--}}
                                <td class="text-right"><b>{{ $row['bonus'] }}</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

						</div>
					</div>
				</div>
			</div>                    









        </div>
    </div>
@endsection
@push('scripts')
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script>
        App.components.admin_settings.init();

		jQuery(document).ready( function () {
			jQuery('#tableBilling').DataTable({
"autoWidth": false,
"pageLength": 50, 
"aaSorting": [
	[ 1, "desc" ]
	],
"aoColumnDefs" : [ 
		{ "aDataSort": [ 0, 1 ], "aTargets": [ 1 ] },
		{ 'bSortable' : false, 'aTargets' : [ "no-sort" ] }
	],
"language": {
    "emptyTable":     '<BR><BR><BR>Пока нет рейтинга<BR><BR><a href="/admin/afillater/partnership" class="btn btn-lg btn-primary"><i class="icon-arrow-right"></i> ПРИГЛАШАЙ ПАРТНЁРОВ</a><BR><BR><BR><BR>',
    "info":           "Показаны строки c _START_ по _END_ из _TOTAL_",
    "infoEmpty":      "",
    "infoFiltered":   "(отфильтровано из _MAX_ строчек)",
	"lengthMenu":     "Показать _MENU_ строчек",
    "loadingRecords": "Чтение...",
    "processing":     "Обработка...",
    "search":         "Поиск:",
    "zeroRecords":    "<BR><BR><BR>Не найдено ни одного значения,<BR>попробуй измени кретиреии поиска<BR><BR><BR><BR>",
    "paginate": {
        "first":      "Первая",
        "last":       "Последняя",
        "next":       "Вперёд",
        "previous":   "Назад"
    } }
});
			} );
    </script>
@endpush