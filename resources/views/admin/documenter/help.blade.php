@section('title', 'Помощь и документация')
@extends('admin.template')

@section('main')

<div class="page-content-wrapper">
	<div class="page-content">
                    <div class="alert-area"></div>


<!--{{--
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="page-title">Помощь и документация</h3>
                    <div class="img-glyph"><span class="icon-question"></span></div>
				<p style="margin-top:0px;">Раздел помощи касается сервиса "Документы", и содержит особенности его использования, ответы на часто задаваемые вопросы, и прочую полезную информацию.</p>
                </div>
            </div>
--}}-->


		<div class="row">

			<div class="col-xs-12 col-sm-6">
				<div class="mt-element-ribbon bg-grey-steel">
					<div class="ribbon ribbon-left ribbon-shadow ribbon-color-danger uppercase">Конфигурирование шаблонов</div>
					<div class="ribbon-content">
						<?PHP $filename = '/storage/help/' . 'Documentation_Templater_Admin.docx'; ?>
						Инструкция по созданию, редактированию и конфигурированию шаблонов. 
						Только для администраторов.<br /><br />
						<a href="<?=$filename?>" target="_blank" class="btn grey-cascade pull-right">Скачать</a>
						<b>Версия файла от <?PHP 
						if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filename)) {
						print date ("d.m.Y H:i", filemtime($_SERVER['DOCUMENT_ROOT'] . $filename));
						}
						?></b>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<div class="mt-element-ribbon bg-grey-steel">
					<div class="ribbon ribbon-left ribbon-shadow ribbon-color-danger uppercase">Управление пользователями</div>
					<div class="ribbon-content">
						<?PHP $filename = '/storage/help/' . 'Documentation_Userser_Admin.docx'; ?>
						Инструкция по управлению пользователями, ролями, правами и доступом. 
						Только для администраторов.<br /><br />
						<a href="<?=$filename?>" target="_blank" class="btn grey-cascade pull-right">Скачать</a>
						<b>Версия файла от <?PHP 
						if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filename)) {
						print date ("d.m.Y H:i", filemtime($_SERVER['DOCUMENT_ROOT'] . $filename));
						}
						?></b>
					</div>
				</div>
			</div>

		</div>



	</div>
</div>
@endsection
