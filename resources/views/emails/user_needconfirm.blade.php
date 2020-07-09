@extends('emails.email_template')

@section('main')

	Только что на сайте <a HREF="{{ Config('global.project.url') }}/admin" STYLE="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">{{ Config('global.project.url') }}</a> была произведена регистрация нового пользователя с адресом <a HREF="mailto:{{ $user->email }}" STYLE="color:#373d43; font-size:14px; font-family:Arial,Sans-serif; text-decoration:none;">{{ $user->email }}</a>. Если ты не регистировался на сайте, пожалуйста, проигнорируй это письмо.<br />
	<br />
	<b>Для завершения регистрации необходимо нажать на ссылку ниже:</b><br />

	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">

		<TR>
			<TD style="padding-right:20px; color:#AAAAAA; font-size:14px; font-family:Arial,Sans-serif;">
				<br>Ссылка
			</TD>
			<TD style="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
				<br><b><a HREF="{{ Config('global.project.url') }}/confirm/{{ $user->confirm_token }}" STYLE="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;"> {{ Config('global.project.url') }}/confirm/{{ $user->confirm_token }}</a></b>
			</TD>
		</TR>

	</TABLE>

@endsection