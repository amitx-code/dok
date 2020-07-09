@section('title', 'Личный кабинет')
@extends('admin.template')

@section('main')






    <div class="page-content-wrapper">
        <div class="page-content">





            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="portlet light">
                        <div class="card-icon main-page-icon">
                            <span class="fa fa-plus font-blue"> </span>
                        </div>
                        <div class="card-title">
                            <span> ЗАПОЛНИТЬ ШАБЛОН </span>
                        </div>
                        <div class="card-desc">
                            <span>Создать документ на основа одного из шаблонов</span>
                        </div>
                        <p style="text-align: center;">
                            <a href="/admin/templater/add" class="btn blue"> ЗАПОЛНИТЬ&nbsp;&nbsp;
                                <i class="fa fa-arrow-right"></i></a>
                        </p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="portlet light">
                        <div class="card-icon main-page-icon">
                            <span class="icon-folder font-blue"> </span>
                        </div>
                        <div class="card-title">
                            <span> ВСЕ ДОКУМЕНТЫ </span>
                        </div>
                        <div class="card-desc">
                            <span>Все мои документы, заполненные ранее</span>
                        </div>
                        <p style="text-align: center;">
                            <a href="/admin/documenter/list" class="btn blue"> ПОСМОТРЕТЬ&nbsp;&nbsp;
                                <i class="fa fa-arrow-right"></i></a>
                        </p>
                    </div>
                </div>


            </div>
            <div class="row">



                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="portlet light">
                        <div class="card-icon main-page-icon">
                            <span class="icon-link font-blue"> </span>
                        </div>
                        <div class="card-title">
                            <span> МОЙ ПРОФИЛЬ </span>
                        </div>
                        <div class="card-desc">
                            <span>Личный данные, контакты, и прочие настройки</span>
                        </div>
                        <p style="text-align: center;">
                            <a href="/admin/settings/personal" class="btn blue"> ИЗМЕНИТЬ&nbsp;&nbsp;
                                <i class="fa fa-arrow-right"></i></a>
                        </p>
                    </div>
                </div>



                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="portlet light">
                        <div class="card-icon main-page-icon">
                            <span class="icon-earphones font-blue"> </span>
                        </div>
                        <div class="card-title">
                            <span> ПОДДЕРЖКА </span>
                        </div>
                        <div class="card-desc">
                            <span>Задайте нам вопрос в режиме онлайн</span>
                        </div>
                        <p style="text-align: center;">
                            <a href="/admin/support/service" class="btn blue"> ПЕРЕЙТИ&nbsp;&nbsp;
				    	<i class="fa fa-arrow-right"></i></a>
                        </p>
                    </div>
                </div>



            </div>









        </div> <!-- Page Content Wrapper -->
    </div> <!-- Page Container -->





















@endsection
@push('scripts')
@endpush
