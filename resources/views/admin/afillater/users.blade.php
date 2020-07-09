@section('title', 'Пользователи партнёрской программы')
@extends('admin.template')

@section('main')






<style>
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem;
}
.card-title {
    margin-bottom: .75rem;
    text-align:left;
}
h5 {
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: 18px;
}
.card .avatar {
	width:20%;
	height:20%;
	display: inline-block;
	-moz-border-radius: 50%!important;
	-webkit-border-radius: 50%!important;
	-khtml-border-radius: 50%!important;
	border-radius: 50%!important;
	float:left;
	margin: 0 10px 0 0;
}
</style>






    <div class="page-content-wrapper">
        <div class="page-content">



            <div class="row">
                <div class="col-sm-12">
                    <div class="portlet light note note-danger">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-link font-red-mint"></i> 
                                <span class="caption-subject font-red-mint sbold uppercase">Твои люди</span>
                            </div>
                        </div>
                        <div class="portlet-body">                    


					На партнёрском уровне <span class="myy-box myy-box-sm"><span class="left" style="margin-right:-5px;"><b>{{ $level }}</b></span></span> 
					по тарифу {!! $tariffs[$tarif]['logo'] !!}<br /><br />
					







@if (!empty($subUsers))
<div class="row">

	@foreach ($subUsers as $subUser)
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6" style="margin-bottom:15px;">
           <div class="card">
            <div class="card-body">
			<img src="{{ User::getAvatar($subUser->id, 'small') }}" alt="" class="avatar">
              <h5 class="card-title">{{ $subUser->name }}</h5>
              <p class="card-text">{{ $subUser->city }}</p>
            </div>
          </div>
        </div>
	@endforeach

	<div style="width:100%;" class="clearfix"></div>
</div>
@endif








                        </div>
                    </div>
                </div>
            </div>








            <div class="row" style="margin-top:30px; margin-bottom:30px;">
                <div class="col-sm-12">
                    @include('admin.afillater.afilliate_matrix')
                </div>
            </div>







        </div>
    </div>
@endsection