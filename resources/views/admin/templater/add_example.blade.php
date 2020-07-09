@section('title', 'Архив документов')
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
</style>



<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>





	<div class="row">
		<div class="col-xs-12">
			<a href="/admin/templater/add/{{ $template->id }}/start" class="btn btn-lg btn-lgg btn-default" 
				style="float:right; margin-left:10px;">
				<i class="icon-action-undo"></i><span class="hidden-xs"> &nbsp; ВЕРНУТЬСЯ</span>
			</a>
                    <h1 class="page-title" style="margin-bottom:0px !important;">
			  	{{ $template->name }}
				@if ($template->customer) 	/ {{ $template->customer }} @endif
				@if ($template->period) 	/ {{ $template->period }} @endif
				@if ($template->type) 		/ {{ $template->type }} @endif
				@if ($template->more) 		/ {!! $template->more !!} @endif
			   </h1>
                    <h1 class="page-title" style="margin:0px !important;">
			  	 <b class="font-yellow">Заполнить информацией из другого документа на выбор:</b>
			  </h3>
		</div>
	</div>
	<br />





<!--{{---------CATEGORIES------------}}-->
<div class="row">
	<div class="col-xs-12">
                                <div class="category-list">
                                    @foreach ($categories as $category)
                                    <a href= @if ($category->id > 0) "/admin/templater/add/{{ $template->id }}/example/{{ $category->id }}" @else "/admin/templater/add/{{ $template->id }}/example" @endif class="btn {{ $category->id == $categoryId ? 'white font-dark' : 'yellow' }}">{{ $category->name }}</a> 
                                    @endforeach
								</div>
	</div>
</div>

 




			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered">                           

                      <table class="table table-striped table-advance table-hover dataTable" id="tableDocumenter">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="hidden-xs">Документ</th>
                                <th class="hidden-xs">Номер</th>
                                <th class="hidden-xs">Дата</th>
                                <th class="hidden-xs">Сумма</th>
                                <th class="hidden-xs">Клиент</th>
                                <th class="hidden-xs">Автор</th>
                                <th class="visible-xs">Документ</th>
					  <th class="no-sort">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach ($documents as $document)
				<tr class="odd gradeX getDataId item-row" data-item-id="{{ $document->id }}">
					<td class="text-center">D{{ str_pad($document->id, 7, "0", STR_PAD_LEFT) }}</td>
					<td class="hidden-xs">{{ $document->name }}</td>
					<td class="hidden-xs text-nowrap">{{ $document->number }}</td>
					<td class="hidden-xs">{{ Helpers::Date($document->date, true) }}</td>
					<td class="hidden-xs text-nowrap text-right">{{ number_format($document->summ, 2, ',', ' ') }}</td>
					<td class="hidden-xs">{{ Helpers::FioShort($document->client) }}</td>
					<td class="hidden-xs">
						<?PHP $temp = User::find($document->user_id); ?>
						{{ Helpers::FioShort($temp->name) }}
					</td>
					<td class="visible-xs">
						{{ $document->name }}
						@if ($document->number) 	№ {{ $document->number }} @endif
						@if ($document->date) 		от {{ Helpers::Date($document->date, true) }} @endif
						@if ($document->summ) 	сумма {{ number_format($document->summ, 2, ',', ' ') }} @endif
					</td>
					<td class="text-center">
						<a href="/admin/templater/add/{{ $template->id }}/start?fill={{ $document->id }}" 
							class="btn btn-sm yellow">
							<span class="hidden-xs">ВЫБРАТЬ</span> <i class="icon-arrow-right"></i>
						</a>
					</td>
				</tr>
                        @endforeach


                        </tbody>
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="hidden-xs">Документ</th>
                                <th class="hidden-xs">Номер</th>
                                <th class="hidden-xs">Дата</th>
                                <th class="hidden-xs">Сумма</th>
                                <th class="hidden-xs">Клиент</th>
                                <th class="hidden-xs">Автор</th>
                                <th class="visible-xs">Документ</th>
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
    <script>
{{--        App.components.admin_settings.init();
--}}
		jQuery(document).ready( function () {
			jQuery('#tableDocumenter').DataTable({
"autoWidth": false,
"pageLength": 50, 
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
    } }
});
			} );
    </script>
@endpush