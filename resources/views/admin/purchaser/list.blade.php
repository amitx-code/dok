@section('title', 'Все документы')
@extends($template)
@section('main')
<?PHP use App\Models\User; ?>


<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
.dropdown-menu>li>a {
	white-space: normal;
	}
.btn-sm {
    padding: 3px 10px;
	margin:0px;
    font-size: 12px;
    line-height: 12px;
	}
table.dataTable thead th, table.dataTable tfoot th {
    font-weight: normal;
	}
.category-list .btn {
	margin: 5px 2px 0 0;
	}
table.dataTable thead th.sorting_asc, 
table.dataTable tfoot th.sorting_asc, 
table.dataTable thead th.sorting_desc, 
table.dataTable tfoot th.sorting_desc {
	font-weight:bold;
	}
table.dataTable tfoot th, table.dataTable tfoot td {
	padding: 8px 10px;
	border-top: 1px solid #111;
}
.category-list .btn {
	border-bottom:0px;
	}
</style>



<div class="page-content-wrapper">
	<div class="page-content">





<!--{{---------CATEGORIES------------}}-->
<div class="row">
	<div class="col-xs-12">
                                <div class="category-list">
                                    @foreach ($categories as $category)
                                    <a href=@if ($category->id > 0) "{{ $urlPrefix }}/admin/purchaser/list/{{ $category->id }}{{ $urlAfter }}" @else "{{ $urlPrefix }}/admin/purchaser/list{{ $urlAfter }}" @endif class="btn {{ $category->id == $categoryId ? 'white font-dark' : 'btn-success' }}">{{ $category->name }}</a>
                                    @endforeach
								</div>
	</div>
</div>






			@if (Route::currentRouteName() != "mobile")
			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered" id="details_tab" style="display:none;">
			@else
			<BR>
			@endif




					<div class="row">
						<div class="col-sm-6" style="margin-bottom:20px;">
						    <div class="input-group input-daterange">
							<div class="input-group-addon">Даты с</div>
							<input type="text" id="min-date" class="form-control date-range-filter" 
								data-date-format="yyyy-mm-dd" placeholder="">
							<div class="input-group-addon">по</div>
							<input type="text" id="max-date" class="form-control date-range-filter" 
								data-date-format="yyyy-mm-dd" placeholder="">
						  </div>
						</div>
						<div class="col-sm-6" style="margin-bottom:20px;">
						    <div class="input-group input-daterange">
							<div class="input-group-addon">Партнёр</div>
							<select name="partner" id="partner" class="form-control">
								<option name="" value=""></option>
								<option name="" value="Азбука Вкуса">Азбука Вкуса</option>
								<option name="" value="Декатлон">Декатлон</option>
								<option name="" value="Русская Ювелирная Мануфкктура">Русская Ювелирная Мануфкктура</option>
								<option name="" value="Эльдорадо">Эльдорадо</option>
							</select>
						  </div>
						</div>
					</div>



                      <table class="table table-striped table-advance dataTable" id="tableMain">
                        <thead>
                            <tr>
                                <th class="hidden-xs text-center">ID</th>
                                <th class="hidden-xs">Документ</th>
                                <th class="">Номер</th>
                                <th class="">Дата</th>
                                <th class="hidden"></th>
                                <th class="">Сумма</th>
                                <th class="hidden-xs">Кэшбек</th>
                                <th class="hidden-xs">Партнёр</th>
					  			<th class="no-sort">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach ($documents as $document)
						<tr class="odd gradeX getDataId item-row" data-item-id="{{ $document->id }}">
							<td class="hidden-xs text-center">D{{ str_pad($document->id, 7, "0", STR_PAD_LEFT) }}</td>
							<td class="hidden-xs">{{ $document->name }}</td>
							<td class="text-nowrap">{{ $document->number }}</td>
							<td class="">{{ Helpers::Date($document->date, true) }}</td>
							<td class="hidden">{{ Helpers::DateStandard02($document->date) }}</td>
							<td class="text-right text-nowrap">{{-- number_format($document->summ, 2, ',', ' ') --}}{{ number_format($document->summ, 2, ',', '') }}</td>
							<td class="hidden-xs text-center text-nowrap
								@if ($document->checked == "completed")
								bg-yellow-soft
								@elseif ($document->checked == "denied")
								bg-red-thunderbird font-white
								@else
								bg-yellow-saffron
								@endif
							"><b>5%</b></td>
							<td class="hidden-xs">{{ $document->client }}</td>
							<td class="text-center">
								<a href="{{ $urlPrefix }}/admin/purchaser/list/card/{{ $document->id }}{{ $urlAfter }}" class="btn btn-sm btn-success">
									<span class="hidden-xs">ДОКУМЕНТ</span> <i class="icon-arrow-right"></i>
								</a>
							</td>
						</tr>
                        @endforeach


                        </tbody>

						<tfoot>
							<tr>
								<th class="hidden-xs" colspan="2">&nbsp;:</th>
								<th colspan="2" style="text-align:right; font-weight:bold;">ИТОГО:</th>
								<th class="hidden"></th>
								<th class="text-right" style="font-weight:bold;"></th>
								<th colspan="3"></th>
						   </tr>
						</tfoot>

                        <thead>
                            <tr>
                                <th class="hidden-xs text-center">ID</th>
                                <th class="hidden-xs">Документ</th>
                                <th class="">Номер</th>
                                <th class="">Дата</th>
                                <th class="hidden"></th>
                                <th class="">Сумма</th>
                                <th class="hidden-xs">Кэшбек</th>
                                <th class="hidden-xs">Партнёр</th>
					  			<th class="no-sort">&nbsp;</th>
                            </tr>
                        </thead>
                    </table>

			@if (Route::currentRouteName() != "mobile")
                        </div>
                    </div>
                </div>
			</div>
			@endif










	</div>
</div>
@endsection
@push('scripts')
<script src="/js/jquery-datepicker-ru.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script type="text/javascript">
jQuery(document).ready( function () {

	searchString = '{{ $searchString }}';

/*{{--######################### ИНИЦИАЛИЗАЦИЯ DataTable ######################################--}}*/
	table = jQuery('#tableMain').DataTable({
"oSearch": { "sSearch": searchString },
"autoWidth": false,
"pageLength": 25, 
"aaSorting": [
	[ 0, "desc" ]
	],
"aoColumnDefs" : [ 
		{ 'bSortable' : false, 'aTargets' : [ "no-sort" ] }
	],
"language": {
    "info":           "Показаны строки c _START_ по _END_ из _TOTAL_",
    "infoEmpty":      "",
    "infoFiltered":   "(отфильтровано из _MAX_ строчек)",
	"lengthMenu":     "Показать _MENU_ строчек",
    "loadingRecords": "Чтение...",
    "processing":     "Обработка...",
    "search":         "Поиск:",
    "zeroRecords":    "<BR><BR><BR>Не найдено ни одного документа,<BR>попробуй измени кретиреии поиска<BR><BR><BR><BR>",
    "paginate": {
        "first":      "Первая",
        "last":       "Последняя",
        "next":       "Вперёд",
        "previous":   "Назад"
    } },

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
//             .column( 5, { page: 'current'} )
                .column( 5, { page: 'all', search:'applied'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
//         pageTotal/100 + '<br><b>' + total/100 + '</b>'
            jQuery( api.column( 5 ).footer() ).html(
                pageTotal/100
            );

        },

		drawCallback: function(settings) {
			var pagination = jQuery(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
			pagination.toggle(this.api().page.info().pages > 1);
			var information = jQuery(this).closest('.dataTables_wrapper').find('.dataTables_info');
			information.toggle(this.api().page.info().pages > 1);
		}

	});




/*{{--######################### ИНИЦИАЛИЗАЦИЯ datepicker ######################################--}}*/
	jQuery('.input-daterange input').each(function() {
	  jQuery(this).datepicker({
		    dateFormat: 'yy-mm-dd'
		});

	});



/*{{--######################### ИНИЦИАЛИЗАЦИЯ ВЫБОРА ДАТ И ПОИСКА ПО НИМ ###################--}}*/
	jQuery.fn.dataTable.ext.search.push(
	  function(settings, data, dataIndex) {
	    var min = jQuery('#min-date').val();
	    var max = jQuery('#max-date').val();
	    var createdAt = data[4] || 0; // Our date column in the table
	
	    if (
		(min == "" || max == "") ||
		(moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max))
	    ) {
		return true;
	    }
	    return false;
	  }
	);
	
	// Re-draw the table when the a date range filter changes
	jQuery('.date-range-filter').change(function() {
	  table.draw();
	});
	
	jQuery('#my-table_filter').hide();




/*{{--######################### ИНИЦИАЛИЗАЦИЯ ВЫБОРА ПАРТНЁРА И ПОИСКА ПО НЕМУ ###############--}}*/
	jQuery('#partner').change(function () {
	    table.search( this.value ).draw();
	} );




/*{{--######################### ИНИЦИАЛИЗАЦИЯ табов ########################--}}*/
	jQuery("#details_tab").show(); 
}); 
</script>
@endpush