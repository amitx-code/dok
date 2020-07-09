@section('title', 'Все партнёры')
@extends('admin.template')

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
.category-list .btn {
	border-bottom:0px;
	}


table.dataTable tr.item-row {
	background-position:center center;
	background-image: linear-gradient(63deg,rgba(0,0,0,.3),rgba(255,255,255,0));
}
table.dataTable tr.item-row td {
	padding-top:25px;
	padding-bottom:25px;
	vertical-align:middle;
	color:#ffffff;
	border-top:10px solid #ffffff;
}
	

table.dataTable img {
	width:60px;
	height:60px;
	border-radius:30px !important;
}
table.dataTable h3 {
	margin:0px;
}
table.dataTable h4 {
	font-weight:bold;
	margin-top:0px;
}
</style>



<div class="page-content-wrapper">
	<div class="page-content">






<!--{{---------CATEGORIES------------}}-->
<div class="row">
	<div class="col-xs-12">
                                <div class="category-list">
                                    @foreach ($categories as $category)
                                    <a href=@if ($category->id > 0) "/admin/partnerer/list/{{ $category->id }}" @else "/admin/partnerer/list" @endif class="btn {{ $category->id == $categoryId ? 'white font-dark' : 'grey-mint' }}">{{ $category->name }}</a> 
                                    @endforeach
								</div>
	</div>
</div>

 




			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered" id="details_tab" style="display:none;">                           

                      <table class="table table-striped table-advance dataTable" id="tableMain">
                        <thead>
                            <tr>
                                <th class="hidden">№</th>
                                <th class="text-center">Логотип</th>
                                <th class="">Партнёр</th>
                                <th class="text-center">Кэшбек</th>
					  <th class="no-sort">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach ($partners as $partner)
				<tr class="odd gradeX getDataId item-row" data-item-id="{{ $partner->id }}" style="background-color:#{{ $partner->color }}; ">
					<td class="hidden">{{ $partner->id }}</td>
					<td class="text-center"><img src="{{ $partner->filename_logo }}" alt=""></td>
					<td class="">
						<h4>{{ $partner->name }}</h4>
						{{ $partner->title }}
					</td>
					<td class="text-center">
						<h3><b>{{ $partner->cashback }}</b> {{ $partner->type }}</h3>
					</td>
					<td class="text-center">
						<a href="/admin/partnerer/list/card/{{ $partner->id }}" class="btn btn-sm btn-default">
							<span class="hidden-xs">ПОДРОБНЕЕ</span> <i class="icon-arrow-right"></i>
						</a>
					</td>
				</tr>
                        @endforeach


                        </tbody>
                        <thead>
                            <tr>
                                <th class="hidden">№</th>
                                <th class="text-center">Логотип</th>
                                <th class="">Партнёр</th>
                                <th class="text-center">Кэшбек</th>
					  <th class="no-sort">&nbsp;</th>
                            </tr>
                        </thead>
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
<script type="text/javascript">
jQuery(document).ready( function () {

	searchString = '{{ $searchString }}';

	jQuery('#tableMain').DataTable({
"oSearch": { "sSearch": searchString },
"autoWidth": false,
"pageLength": 50, 
"aaSorting": [
	[ 2, "asc" ]
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
    "zeroRecords":    "<BR><BR><BR>Не найдено ни одного партнёра,<BR>попробуй измени кретиреии поиска<BR><BR><BR><BR>",
    "paginate": {
        "first":      "Первая",
        "last":       "Последняя",
        "next":       "Вперёд",
        "previous":   "Назад"
    } },

		drawCallback: function(settings) {
			var pagination = jQuery(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
			pagination.toggle(this.api().page.info().pages > 1);
			var information = jQuery(this).closest('.dataTables_wrapper').find('.dataTables_info');
			information.toggle(this.api().page.info().pages > 1);
		}

	});

});

jQuery(document).ready( function () {
	jQuery("#details_tab").show(); 
}); 
</script>
@endpush