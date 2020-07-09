@section('title', 'Просмотр документа')
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
</style>



<div class="page-content-wrapper">
<div class="page-content">






@if ($document->user_role_id > $user->role_id)
	<div class="alert-area">
		<div class="custom-alerts alert alert-danger fade in">
			<h1>
				<i class="icon-ban"></i> 
				<span>Отсутствуют права доступа к документу</span>
			</h1>
			<br />
			<p>Документ создал {{ $document->user_name }} - пожалуйста обратитесь к руководителю для предоставления доступа</p>
			<br />
		</div>
	</div>
@else






	<div class="alert-area"></div>
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
					Дата
					<span>{{ Helpers::Date($document->date, true) }} года</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->summ)
				<li class="list-group-item">
					Сумма
					<span>{{ number_format($document->summ, 2, ',', '&nbsp;') }}</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->client)
				<li class="list-group-item">
					Клиент
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
				<li class="list-group-item">
					Документ создал
					<span>{{ $document->user_name }}</span>
					<div style="clear:both;"></div>
				</li>
@if ($document->created)
				<li class="list-group-item">
					Дата и время создания
					<span>{{ Helpers::DateTime($document->created, true) }}</span>
					<div style="clear:both;"></div>
				</li>
@endif
@if ($document->filename)
				<li class="list-group-item">
					Название файла<br />
					<a href="/admin/documents/{{ $document->id }}/view/doc">
					{{ $document->filename }}
					</a>
					<div style="clear:both;"></div>
				</li>
@endif

			</ul>
		</div>





		<div class="col-xs-12 col-sm-6 text-center">
@if ($document->filename)
			<a href="/admin/documents/{{ $document->id }}/view/doc" class="btn btn-lg btn-lgg btn-success" style="width:100%;">
				<i class="icon-arrow-down"></i> &nbsp; СКАЧАТЬ WORD</a>
			<br />
			<br />
{{--
			<a href="/admin/documents/{{ $document->id }}/view/pdf" class="btn btn-lg btn-lgg btn-primary" style="width:100%;">
				<i class="icon-arrow-down"></i> &nbsp; СКАЧАТЬ PDF</a>
			<br />
			<br />
--}}
			<a href="#modal_email" class="btn btn-lg btn-lgg btn-warning" style="width:100%;"
				data-toggle="modal">
				<i class="icon-envelope-letter"></i> &nbsp; ОТПРАВИТЬ ПО E-MAIL</a>
			<br />
			<br />
			<hr />
			<br />
@endif
			<a href="/admin/documents" class="btn btn-lg btn-lgg btn-default" style="width:100%;">
				<i class="icon-action-undo"></i> &nbsp; ВСЕ ДОКУМЕНТЫ</a>
		</div>




	</div>







	<div class="modal fade bs-modal-lg in" id="modal_email" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<form name="emailForm" id="emailForm" action="/admin/documents" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><b>Отправка документа по E-mail</b></h4>
				</div>
				<div class="modal-body">
						<div class="form-group">
							<label>
								Получатель <span class="font-red-mint">*</span>
							</label>
								<input type="text" name="to" class="form-control" value='{{ $document->email }}'/>
						</div>
						<div class="form-group">
							<label>
								Отправитель <span class="font-red-mint">*</span>
							</label>
								<input type="text" name="from" class="form-control" value='{{ $user->email }}'/>
						</div>
						<div class="form-group">
							<label>
								Тема письма <span class="font-red-mint">*</span>
							</label>
								<input type="text" name="subject" class="form-control" value='Отправляю Вам документ'/>
						</div>
						<div class="form-group">
							<label>
								Сопроводительный текст <span class="font-red-mint">*</span>
							</label>
								<textarea type="text" name="texr" class="form-control" rows="8">Ув. {{ $document->client }}!

Во вложении к данному письму направляю Вам документ:
{{ $document->filename }}

С уважением,
{{ $user->name }}
{{ $user->email }}</textarea>
						</div>
						<div class="form-group">
							Вложенный файл: 
							<a href="/admin/documents/{{ $document->id }}/view/doc">
								{{ $document->filename }}
							</a>
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn dark btn-outline" data-dismiss="modal">Отмена</button>
{{--						<button type="submit" class="btn green">Отправить</button>--}}
				<a href="/admin/documents" type="button" class="btn green">Отправить</a>
				</div>
			</div>
			</form>
		</div>
	</div>







@endif
</div>
</div>
@endsection
