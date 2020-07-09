@section('title', 'Сотрудники организации')
@extends('admin.template')

@section('main')
<?PHP use App\Models\ConfigerUsersRoles; ?>

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

<!--{{--
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="page-title">Пользователи и сотрудники организации</h3>
                    <div class="img-glyph"><span class="icon-users"></span></div>
				<p style="margin-top:0px;">В процессе разработки. В процессе разработки. В процессе разработки.</p>
                </div>
            </div>
--}}-->





			<div class="row">
				<div class="col-lg-12">
                    <div class="portlet-body">
                        <div class="portlet light bordered">                           

                      <table class="table table-striped table-advance table-hover dataTable" id="tableMain">
                        <thead>
                            <tr>
                                <th class="hidden-xs text-center">ID</th>
                                <th class="hidden-xs">ФИО</th>
                                <th class="hidden-xs">Роль</th>
                                <th class="hidden-xs">Email</th>
                                <th class="hidden-xs">Телефон</th>
                                <th class="hidden-xs">Адрес</th>
                                <th class="hidden-xs">Создан</th>
                                <th class="visible-xs">Сотрудник</th>
<!--{{--					  <th class="no-sort">&nbsp;</th>--}}-->
                            </tr>
                        </thead>
                        <tbody>


                        @foreach ($rows as $row)
				<tr class="odd gradeX getDataId item-row" data-item-id="{{ $row->id }}">
					<td class="hidden-xs text-center">{{ $row->id }}</td>
					<td class="hidden-xs"><b>{{ $row->name }}</b></td>
					<td class="hidden-xs">
						<a href="/admin/configer/roles" 
						class="label label-sm bg-{{ ConfigerUsersRoles::find($row->role_id)->color }}">
							<b>{{ ConfigerUsersRoles::find($row->role_id)->name }}</b>
						</a>
					</td>
					<td class="hidden-xs"><a href="mailto:{{ $row->email }}" target="_blank">{{ $row->email }}</td>
					<td class="hidden-xs">{{ $row->phone }}</td>
					<td class="hidden-xs">
						{{ $row->country }} {{ $row->zip }} {{ $row->city }} {{ $row->address }}
					</td>
					<td class="hidden-xs">{{ Helpers::DateTime($row->created, true) }}</td>
					<td class="visible-xs">
						<b>{{ $row->name }}</b><BR>
						<a href="/admin/configer/roles" 
						class="label label-sm bg-{{ ConfigerUsersRoles::find($row->role_id)->color }}">
							<b>{{ ConfigerUsersRoles::find($row->role_id)->name }}</b>
						</a><BR>
						{{ $row->email }} {{ $row->phone }}
					</td>
<!--{{--
					<td class="text-center">
						<a href="#" class="btn btn-sm btn-success">
							<span class="hidden-xs">ОТКРЫТЬ</span> <i class="icon-arrow-right"></i>
						</a>
					</td>
--}}-->
				</tr>
                        @endforeach


                        </tbody>
                        <thead>
                            <tr>
                                <th class="hidden-xs text-center">ID</th>
                                <th class="hidden-xs">ФИО</th>
                                <th class="hidden-xs">Роль</th>
                                <th class="hidden-xs">Email</th>
                                <th class="hidden-xs">Телефон</th>
                                <th class="hidden-xs">Адрес</th>
                                <th class="visible-xs">Сотрудник</th>
                                <th class="hidden-xs">Создан</th>
<!--{{--					  <th class="no-sort">&nbsp;</th>--}}-->
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
		jQuery(document).ready( function () {
			jQuery('#tableMain').DataTable({
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
    "zeroRecords":    "<BR><BR><BR>Не найдено ни одного пользователя,<BR>попробуй измени кретиреии поиска<BR><BR><BR><BR>",
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