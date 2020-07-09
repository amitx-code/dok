@section('title', 'Все партнёры')
@extends('admin.template')

@section('main')
<?PHP use App\Models\User; ?>


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
.category-list .btn {
	margin: 5px 2px 0 0;
	}
.category-list .btn {
	border-bottom:0px;
	}
.overflow {
	display:block;
	height:20px;
	overflow:hidden;
	}
</style>



<link rel="stylesheet" type="text/css" href="/css/public_plugins/font-awesome.min.css" />


<style>
.mb-30 { margin-bottom: 30px !important; }
.pl-20 { padding-left: 20px !important; }
.text-white {
    color: #fff!important;
}
h5.text-white {
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: 18px;
    margin-top:0px;
}
h6 {
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 18px;
    margin-top:0px;
}
.img-fluid {
    max-width: 100%;
    height: auto;
}
.float-right {
    float: right!important;
}
.d-inline-block {
    display: inline-block!important;
}
ul {
    margin: 0px;
    padding: 0px;
}
.list-unstyled {
    padding-left: 0;
    list-style: none;
}
.theme-color {
	color: #AE86F4;
}
.text-gray {
    color: #999999;
}

.listing-post .blog-name .blog-name-left {
	width: 50px; height: 50px;  
	border-radius: 50% !important; color: #ffffff; text-align: center; line-height: 50px; float: left; margin-right: 20px;
	background-position: center center;
	background-repeat:no-repeat;
	background-size:cover;
}
.listing-post .blog-name .blog-name-right { display: table-cell; }
.listing-post .listing-post-info { background: #ffffff; padding: 20px; }
.listing-post .listing-post-info .listing-post-meta ul li { display: inline-block; margin-right: 5px; padding-right: 10px; border-right: 2px solid rgba(0,0,0,0.1); }
.listing-post .listing-post-info .listing-post-meta ul li:last-child { border-right: 0; }
.listing-post .listing-post-info .listing-post-meta ul li, 
.listing-post .listing-post-info .listing-post-meta ul li a {
	font-weight: 800; color: #323232;
}

.blog-bg {
	background-image:url(/img_public/bg/01.jpg);
	background-position: center center;
	background-repeat:no-repeat;
	background-size:cover;
}

.blog-overlay { position: relative; /*height: 100%; */text-align: left; z-index: 2; transition: all 0.5s ease-in-out; -o-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; -moz-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; }
.blog-overlay .blog-name { padding-left: 30px; position: absolute; bottom: 20px; left: 0; right: 0; width: 100%; z-index: 2; }
.blog-overlay .blog-name span { transition: all 0.5s ease-in-out; -o-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; -moz-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; }
.blog-overlay.white-bg:before, .blog-overlay.dark-theme-bg:before, .blog-overlay.theme-bg:before { display: none; }
.blog-overlay .blog-image { overflow: hidden; position: relative;  }
.blog-overlay .blog-image img { transition: all 0.5s ease-in-out; -o-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; -moz-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; }
.blog-02 .isotope, .blog-02 .masonry { margin: 0; }

.blog-overlay .blog-icon {  position: absolute; top: 0px;  width: 100%; z-index: 2; }
.blog-overlay .blog-icon .date { background: #050801; padding: 10px 15px; text-align: center; color: #ffffff; }
.blog-overlay .blog-icon .link { font-size: 20px; padding: 10px 15px; text-align: center; color: #ffffff; }

.blog-overlay blockquote { border:0; font-size: 16px; font-style: italic; }
.blog-overlay blockquote.quote:before { top: -40px; }
.blog-overlay.white-bg blockquote, .blog-overlay.white-bg cite { transition: all 0.5s ease-in-out; -o-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; -moz-transition: all 0.5s ease-in-out; -ms-transition: all 0.5s ease-in-out; }

.blog-overlay:hover .blog-image img { -webkit-transform:scale(1.20);  -moz-transform:scale(1.20); -ms-transform:scale(1.20);  -o-transform:scale(1.20);  transform:scale(1.20); }
.blog-overlay:hover:before  { z-index: 1; }



.isotope-filters { display: table; margin: 0 0 20px 0; text-align: left; }
.isotope-filters.text-left { display: block; margin: 30px 0; text-align: left; }
.isotope-filters a {margin: 4px 2px;cursor: pointer;padding: 6.5px 25px;font-size: 15px;border-radius: 3px;background: rgba(255,255,255,0.3);color: #363636;font-weight: 500;border: 1px solid #AE86F4;transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;}
.isotope-filters  a:focus { outline: none;  outline-style: none; outline-offset:0; }
.isotope-filters  a:hover { background: #AE86F4; color: #fff; border-color: #84ba3f; }
.isotope-filters  a.active {background: #AE86F4; color: #fff; }
.isotope-filters select {height:36px; font-size: 15px; border-radius: 0px!important; background: rgba(255,255,255,0.3);color: #363636;font-weight: 500; border: 1px solid #AE86F4;transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;}


.pagination {
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    margin-bottom:20px;
    list-style: none;
    border-radius: .25rem;
    -webkit-box-pack: center!important;
    -ms-flex-pack: center!important;
    justify-content: center!important;
}
.pagination li a {
    text-align: center;
    font-size: 15px;
    font-weight: 500;
    margin: 0 4px;
    color: #363636;
    	border: 1px solid #AE86F4;
    border-radius: 3px;
    background: rgba(255,255,255,0.3);
    transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;
}
.pagination li a:hover {background: #AE86F4; color: #fff; border-color: #84ba3f;}
.pagination li a.active {background: #AE86F4; color: #fff; font-weight:bold; }
.pagination li a.fast {
    background: none;
    border:0px;
    padding:7px 0;
}
.pagination li a.fast i {
	font-size:18px;
}
.pagination li a.fast:hover,
.pagination li a.fast:hover i {
    color: #AE86F4;
}
.pagination li div {
    text-align: center;
    font-size: 15px;
    font-weight: 500;
    margin: 5px;
    color: #363636;
}

@media (max-width:768px) {
.pagination li a {
	margin: 0 2px;
	font-size: 12px;
	padding: 3px 6px;
}
.pagination li a.fast {
    padding:6px 0;
}
}
</style>



<div class="page-content-wrapper">
	<div class="page-content">






<!--{{---------CATEGORIES------------}}-->
<!--{{--
<div class="row">
	<div class="col-xs-12">
                                <div class="category-list">

                                    @foreach ($categories as $category)
                                    <a href=@if ($category->id > 0) "/admin/partnerer/list/{{ $category->id }}" @else "/admin/partnerer/list" @endif class="btn {{ $category->id == $categoryId ? 'white font-dark' : 'grey-mint' }}">{{ $category->name }}</a> 
                                    @endforeach
								</div>
	</div>
</div>
--}}-->

 



<!--{{--
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
--}}-->





	<!--================================= РЕЗУЛЬТАТЫ ПОИСКА -->
	{{--@if (count($search) > 2)--}}
	<div class="alert-area">
		<div class="alert alert-success fade in" id="alertMore" style="font-size:18px;">
		<a type="button" class="close" href="/admin/partnerer/list"></a>
			<form action="?" method="get">

					<div class="caption" style="margin-bottom:10px;">
						<i class="fa fa-search"></i>
						<span class="caption-subject uppercase">Поиск по магазинам и услугам</span>
					</div>

					<div class="row">
						<div class="col-xs-12 col-md-3">
                   					<input type="text" name="text" class="form-control" 
								value='@if (!empty($search['text'])){{ $search['text'] }}@endif' 
								style="margin-top:3px; margin-bottom:10px;">
						</div>
						<div class="col-xs-12 col-md-2">
							<div class="mt-checkbox-list">
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input type="checkbox" name="now" value="on" 
										    @if (!empty($search['now'])) checked @endif> 
										    Работает сейчас
                                                                <span></span>
                                                            </label>
							</div>
						</div>
						<div class="col-xs-12 col-md-2">
							<div class="mt-checkbox-list">
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input type="checkbox" name="near" value="on" 
										    @if (!empty($search['near'])) checked @endif> 
										    Находится рядом
                                                                <span></span>
                                                            </label>
							</div>
						</div>
						<div class="col-xs-12 col-md-2">
							<div class="mt-checkbox-list">
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input type="checkbox" name="online" value="on" 
										    @if (!empty($search['online'])) checked @endif> 
										    Есть услуги онлайн
                                                                <span></span>
                                                            </label>
							</div>
						</div>
						<div class="col-xs-12 col-md-3">
							<div class="text-right">
								<button class="btn bg-green-jungle font-white">
									Новый поиск <i class="fa fa-arrow-right"></i>
								</button>
							</div>
						</div>

					</div>
			</form>
		</div>
	</div>
	{{--@endif--}}
	<!--================================= ./// РЕЗУЛЬТАТЫ ПОИСКА -->







<!--================================= Список партнёров -->
<section class="page-section-ptb listing-places blog-bg" style="padding:30px;">





	<!--=============== Популярные категории -->
	<div class="isotope-filters">

		<div style="float:left; margin:4px 23px 0 2px;">
			<select name="territory" id="territory" style="border: 3px solid #AE86F4;">
				@foreach ($territories as $territory)
				<option value="/admin/partnerer/list/{{ $categoryId }}-{{ $territory->id }}{{ $search['url2'] }}"
				{{ $territory->id == $territoryId ? 'selected' : '' }}
				>{{ $territory->name }}</option>
				@endforeach
			</select>
		</div>

		@foreach ($categories as $category)
		<a href="/admin/partnerer/list/{{ $category->id }}-{{ $territoryId }}{{ $search['url2'] }}" 
		class="btn {{ $category->id == $categoryId ? 'active' : '' }}">{{ $category->name }}</a> 
		@endforeach
	</div>
	<!--=============== Популярные категории -->







		@if ($pages['total'] > 1)
		<ul class="pagination">
			@if (!empty($pages['start']))
			<li><a class="fast" href="?page={{ $pages['start'] }}{{ $search['url1'] }}"><i class="fa fa-fast-backward"></i></a></li>
			@endif
			@for ($i = $pages['min']; $i <= $pages['max']; $i++)
			<li><a @if ($i == $pages['now']) class="active" @endif href="?page={{ $i }}{{ $search['url1'] }}">{{ $i }}</a></li>
			@endfor
			@if (!empty($pages['end']))
			<li><a class="fast" href="?page={{ $pages['end'] }}{{ $search['url1'] }}"><i class="fa fa-fast-forward"></i></a></li>
			@endif
		</ul>
		@endif






<div class="row">

			@foreach ($partners as $partner)
				<div class="col-lg-4 col-md-6 mb-30">
				   <div class="listing-post">
				    <div class="blog-overlay">
					<a href="/admin/partnerer/list/card/{{ $partner->id }}-{{ $territoryId }}">
					<div class="blog-image">
					  <img class="img-fluid" src="{{ $partner->filename_img }}" alt="">
					</div>
					<div class="blog-name clearfix pl-20">
					  <div class="blog-name-left" style="background-color:#{{ $partner->color }}; background-image:url('{{ $partner->filename_logo }}') !important;"></div>
					  <div class="blog-name-right">
					    <h5 class="text-white overflow">{{ $partner->name }}</h5>
					    <span class="text-white overflow">{{ $partner->title }}</span>
					  </div>
					</div>
					</a>
				     </div>
				     <div class="listing-post-info">
					 <div class="listing-post-meta clearfix">
						<ul class="list-unstyled d-inline-block">
						    @if ($partner->cashback)
						    <li>
						      <span class="text-gray">Кэшбэк</span>
						    	@if ($partner->cashback_to=="Y") до @endif
						    	{{ $partner->cashback }} 
						      <span class="text-gray">{{ $partner->cashback_type }}</span>
						    </li>
						    @endif
						    @if ($partner->discount)
						    <li>
						      <span class="text-gray">Скидка</span>
						    	@if ($partner->discount_to=="Y") до @endif
						    	{{ $partner->discount }} 
						      <span class="text-gray">{{ $partner->discount_type }}</span>
						    </li>
						    @endif
						</ul>
						<div class="text-right mt-1">
						  <h6 class="theme-color">
						  @foreach ($categories as $category)
						  @if($category->id == $partner->category_id)
						  {{ $category->name }}
						  @endif
						  @endforeach
						  </h6>
						</div>
					  </div>
				     </div>
				 </div>
				</div>
			@endforeach

	</div>





		@if ($pages['total'] > 1)
		<ul class="pagination">
			@if (!empty($pages['start']))
			<li><a class="fast" href="?page={{ $pages['start'] }}{{ $search['url1'] }}"><i class="fa fa-fast-backward"></i></a></li>
			@endif
			@for ($i = $pages['min']; $i <= $pages['max']; $i++)
			<li><a @if ($i == $pages['now']) class="active" @endif href="?page={{ $i }}{{ $search['url1'] }}">{{ $i }}</a></li>
			@endfor
			@if (!empty($pages['end']))
			<li><a class="fast" href="?page={{ $pages['end'] }}{{ $search['url1'] }}"><i class="fa fa-fast-forward"></i></a></li>
			@endif
		</ul>
		@endif





</section>
<!--================================= Список партнёров -->








	</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
jQuery(document).ready( function () {

//	jQuery("#details_tab").show(); 

	jQuery("#territory").change(function() {
		window.location.href = jQuery(this).val();
	});

}); 
</script>
@endpush