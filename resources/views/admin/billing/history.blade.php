@section('title', 'История операций')
@extends('admin.template')

@section('main')


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

                    @if ($pay_result == "success")
                        <div class="alert alert-block alert-success fade in">
                            <button class="close" data-dismiss="alert"></button>
                            <b>Баланс успешно пополнен!</b><br />
Оплаченные деньги уже зачисллены на баланс, и теперь их сразу же можно тратить на абсолютно любые услуги сайта.
                        </div>
                    @elseif ($pay_result == "failure")
                        <div class="alert alert-block alert-danger fade in">
                            <button class="close" data-dismiss="alert"></button>
                            <b>Баланс не был пополнен!</b><br />
К сожалению, оплаченные деньги не были внесены на баланс, потому что платёжный сервис не сообщил об успешной оплате. <br />
<br />
Если на предыдущих шагах твоя оплата завершилась успешно, то стоит подождать (5-10 минут). Если после этого оплата всё же не будет зачислена, пожалуйста напиши детали оплаты <a href="/admin/support"  class="alert-link">в нашу техподдержку</a> - мы разберёмся.<br /><br />
Также можно <a href="/admin/billing/pay"  class="alert-link">ещё раз попробовать пополнить баланс</a>.
                        </div>
                    @endif





<!--{{--
            <div class="row">
                <div class="col-lg-12">

                    @if (count($history) > 0)
			       	 <a href="/admin/billing/pay" class="btn btn-lg btn-danger pull-right"><i class="icon-basket-loaded"></i> ПОПОЛНИТЬ<span class="hidden-xs"> БАЛАНС</span></a>
                    @endif

                    <h3 class="page-title">История платежей и расходов</h3>

                    <div class="img-glyph"><span class="icon-wallet"></span></div>
					<p>В нашем сервие действует система оплаты и расходов как в сотовой связи: достаточно оплатить раз, а потом расходовать деньги по заказанным услугам. Оплаченные деньги не исчезают сразу, а просто добавляются на личный счёт.</p>
                    @if (!empty($doc->text))
                        {{ $doc->text }}
                    @endif
                </div>
            </div>
--}}-->







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
    "emptyTable":     '<BR><BR><BR>Пока нет ни одного платежа или заказа<BR><BR><a href="/admin/billing/pay" class="btn btn-lg btn-primary"><i class="icon-basket-loaded"></i> ПОПОЛНИТЬ БАЛАНС</a><BR><BR><BR><BR>',
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