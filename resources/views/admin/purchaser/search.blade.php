@section('title', 'Поиск документа')
@extends($template)
@section('main')

<style>
.superbig {
	margin:30px 0;
	font-size:36px;
	font-weight: 300;
	font-family: Open Sans,sans-serif;
	padding:15px;
	text-align:center;
	width:100%;
}
h1 i {
	font-size:92px;
}
</style>


<div class="page-content-wrapper">
	<div class="page-content">
		<div class="alert-area"></div>
			<form action="list" method="post">
				{{ csrf_field() }}
				<input type="hidden" name="api_token" value="{{ $request->api_token }}">

				<div class="row">
					<div class="col-xs-12">
						<div class="portlet light">
							<div class="portlet-body text-center">
								<br />
								<h1>
									<i class="icon-magnifier"></i><br /><br />
									НАЙТИ ДОКУМЕНТ
								</h1>
								<div class="row">
									<div class="col-xs-12 col-sm-3">&nbsp;</div>
									<div class="col-xs-12 col-sm-6">
										<input type="text" name="searchString" class="superbig" placeholder="Что ищем?" />
									</div>
									<div class="col-xs-12 col-sm-3">&nbsp;</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mybuttons">
					<div class="col-xs-12 text-center">
						<button class="btn btn-lg btn-lgg btn-default">
							<i class="icon-magnifier"></i> &nbsp; ИСКАТЬ</button>
						<br /><br />
					</div>
				</div>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
</script>
@endpush
