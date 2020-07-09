@section('title', 'Заполнить шаблон')
@extends('admin.template')

@section('main')


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





<!--{{--
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="page-title">Создать документ</h3>
                    <div class="alert-area"></div>
                    <div class="img-glyph"><span class="icon-user"></span></div>
				<p style="margin-top:0px;">Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. Тут будет текст. </p>
                </div>
            </div>
--}}-->





<!--{{---------CATEGORIES------------}}-->
<div class="row">
	<div class="col-xs-12">
                                <div class="category-list">
                                    @foreach ($categories as $category)
                                    <a href= @if ($category->id > 0) "/admin/templater/add/{{ $category->id }}" @else "/admin/templater/add" @endif class="btn {{ $category->id == $categoryId ? 'white font-dark' : 'blue' }}">{{ $category->name }}</a> 
                                    @endforeach
								</div>
	</div>
</div>

 




			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered">                           

                      <table class="table table-striped table-advance table-hover dataTable" id="tableTemplater">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="hidden-xs">Шаблон</th>
                                <th class="hidden-xs">Клиент</th>
                                <th class="hidden-xs">Период</th>
<!--{{--
                                <th class="hidden-xs">Тип</th>
                                <th class="hidden-xs">Уточнение</th>
--}}-->
                                <th class="visible-xs">Шаблон</th>
<!--{{--
					  <th class="no-sort hidden-xs">&nbsp;</th>
--}}-->
                                <th class="hidden-xs no-sort text-center">Заполнить</th>
                                <th class="no-sort text-center">Заполнить</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach ($templates as $template)
				<tr class="odd gradeX getDataId item-row" data-item-id="{{ $template->id }}">
					<td class="text-center">T{{ str_pad($template->id, 7, "0", STR_PAD_LEFT) }}</td>
					<td class="hidden-xs">{{ $template->name }}</td>
					<td class="hidden-xs">{{ $template->customer }}</td>
					<td class="hidden-xs">{{ $template->period }}</td>
<!--{{--
					<td class="hidden-xs">{{ $template->type }}</td>
					<td class="hidden-xs">{!! $template->more !!}</td>
--}}-->
					<td class="visible-xs">
						{{ $template->name }}
						@if ($template->customer) 	/ {{ mb_substr($template->customer,0,3) }} @endif
						@if ($template->period) 	/ {{ mb_substr($template->period,0,3) }} @endif
						@if ($template->type) 		/ {{ mb_substr($template->type,0,3) }} @endif
						@if ($template->more) 		/ {{ mb_substr($template->more,0,3) }} @endif
					</td>
<!--{{--
					<td class="text-center hidden-xs">
						<div class="btn-group">
							<a href="#" class="btn btn-sm font-dark" data-toggle="dropdown" data-hover="dropdown"
								data-close-others="true" aria-expanded="true"><i class="icon-question"></i></a>
							<ul class="dropdown-menu text-right">
								<li><a>{{ $template->description }}</a></li>
							</ul>
						</div>
					</td>
--}}-->
                    <td class="text-center hidden-xs">
                        @if ($template->filename_html)
                            <a href="/admin/templater/add/{{ $template->id }}/startvis" class="btn btn-sm green">
                                <span class="hidden-xs">ВИЗУАЛЬНО</span> <i class="icon-arrow-right"></i>
                            </a>
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($template->filename)
                            <a href="/admin/templater/add/{{ $template->id }}/start" class="btn btn-sm blue">
                                <span class="hidden-xs">ПО ШАГАМ</span> <i class="icon-arrow-right"></i>
                            </a>
                        @else
                            &nbsp;
                        @endif
                    </td>
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
{{--        App.components.admin_settings.init();
--}}
		jQuery(document).ready( function () {
			jQuery('#tableTemplater').DataTable({
"autoWidth": false,
"pageLength": 50, 
"aaSorting": [
	[ 1, "asc" ]
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