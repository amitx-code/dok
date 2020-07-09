@section('title', 'Выбор тарифа')
@extends('admin.template')

@section('main')




<style>
.btn-lgg {
	padding: 35px 25px;
	font-size: 24px;
	}
.mt-element-list .list-simple.mt-list-container ul>.mt-list-item>.list-item-content {
	padding-right:0px;
	padding-left:30px;
}
.mt-element-list .list-simple.mt-list-container {
	border:0px;
	padding:0px;
}
</style>





<div class="page-content-wrapper">
<div class="page-content">
<div class="alert-area"></div>





	<div class="row">




		<div class="col-md-4">
                    <div class="portlet light">
                        <div class="portlet-body">
					<div class="mt-element-step">
                                            <div class="row step-background">
                                                <div class="col-xs-12 bg-grey-steel mt-step-col active">
                                                    <div class="mt-step-number">1</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Простой</div>
                                                    <div class="mt-step-content font-grey-cascade">Что тут будет написано пока не знаем</div>
                                                </div>
                                            </div>
					</div>
					<br />
					<p>Гендер изменяем. Комплекс неизменяем. Здесь автор сталкивает два таких достаточно далёких друг от друга явления как сновидение латентно.</p>
					<div class="mt-element-list">
						<div class="mt-list-container list-simple">
                                                <ul>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Повышенный кэшбек</div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container"><i class="icon-close"></i></div>
                                                        <div class="list-item-content font-grey-salsa"><s>Личный менеджер</s></div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container"><i class="icon-close"></i></div>
                                                        <div class="list-item-content font-grey-salsa"><s>Ежегодные подарки</s></div>
                                                    </li>
                                                </ul>
						</div>
					</div>
					<br />
					<p>Как мы уже знаем, восприятие абсурдно осознаёт оппортунический ассоцианизм. Ригидность отталкивает институциональный кризис. Парадигма одинаково отражает конфликтный психоанализ.</p>

					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=500">
							      <div class="col-xs-12 bg-grey mt-step-col active">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />500
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей в месяц</div>
                                                </div>
							</a>
                              	</div>
					</div>
					<br />
					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=4999">
							      <div class="col-xs-12 bg-grey mt-step-col active">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />4 999
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей за весь год</div>
                                                </div>
							</a>
                              	</div>
					</div>
                        </div>
                    </div>
		</div>



		<div class="col-md-4">
                    <div class="portlet light">
                        <div class="portlet-body">
					<div class="mt-element-step">
                                            <div class="row step-background">
                                                <div class="col-xs-12 bg-grey-steel mt-step-col done">
                                                    <div class="mt-step-number">2</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Удачный</div>
                                                    <div class="mt-step-content font-grey-cascade">Что тут будет написано пока не знаем</div>
                                                </div>
                                            </div>
					</div>
					<br />
					<p>Гендер изменяем. Комплекс неизменяем. Здесь автор сталкивает два таких достаточно далёких друг от друга явления как сновидение латентно.</p>
					<div class="mt-element-list">
						<div class="mt-list-container list-simple">
                                                <ul>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Повышенный кэшбек</div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Личный менеджер</div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container"><i class="icon-close"></i></div>
                                                        <div class="list-item-content font-grey-salsa"><s>Ежегодные подарки</s></div>
                                                    </li>
                                                </ul>
						</div>
					</div>
					<br />
 					<p>Как мы уже знаем, восприятие абсурдно осознаёт оппортунический ассоцианизм. Ригидность отталкивает институциональный кризис. Парадигма одинаково отражает конфликтный психоанализ.</p>

					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=1000">
							      <div class="col-xs-12 bg-grey mt-step-col done">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />1 000
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей в месяц</div>
                                                </div>
							</a>
                              	</div>
					</div>
					<br />
					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=9999">
							      <div class="col-xs-12 bg-grey mt-step-col done">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />9 999
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей за весь год</div>
                                                </div>
							</a>
                              	</div>
					</div>
                        </div>
                    </div>
		</div>


		<div class="col-md-4">
                    <div class="portlet light">
                        <div class="portlet-body">
					<div class="mt-element-step">
                                            <div class="row step-background">
                                                <div class="col-xs-12 bg-grey-steel mt-step-col active error">
                                                    <div class="mt-step-number">3</div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">VIP</div>
                                                    <div class="mt-step-content font-grey-cascade">Что тут будет написано пока не знаем</div>
                                                </div>
                                            </div>
					</div>
					<br />
					<p>Гендер изменяем. Комплекс неизменяем. Здесь автор сталкивает два таких достаточно далёких друг от друга явления как сновидение латентно.</p>
					<div class="mt-element-list">
						<div class="mt-list-container list-simple">
                                                <ul>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Повышенный кэшбек</div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Личный менеджер</div>
                                                    </li>
                                                    <li class="mt-list-item">
                                                        <div class="list-icon-container done"><i class="icon-check"></i></div>
                                                        <div class="list-item-content">Ежегодные подарки</div>
                                                    </li>
                                                </ul>
						</div>
					</div>
					<br />
					<p>Как мы уже знаем, восприятие абсурдно осознаёт оппортунический ассоцианизм. Ригидность отталкивает институциональный кризис. Парадигма одинаково отражает конфликтный психоанализ.</p>

					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=5000">
							      <div class="col-xs-12 bg-grey mt-step-col error">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />5 000
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей в месяц</div>
                                                </div>
							</a>
                              	</div>
					</div>
					<br />
					<div class="mt-element-step">
                              	<div class="row step-default">
							<a href="/admin/billing/pay?message=more&summ=49999">
							      <div class="col-xs-12 bg-grey mt-step-col error">
                                                    <div class="mt-step-title uppercase font-grey-cascade">
								    	<i class="icon-basket-loaded"></i><br />49 9999
								    </div>
                                                    <div class="mt-step-content font-grey-cascade">рублей за весь год</div>
                                                </div>
							</a>
                              	</div>
					</div>
                        </div>
                    </div>
		</div>



	</div>    




</div>
</div>
@endsection

@push('scripts')
    <script>
        App.components.admin_payment.init({});
    </script>
@endpush