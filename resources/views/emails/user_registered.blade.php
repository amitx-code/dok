@extends('emails.email_template')

@section('main')

	Сохрани это письмо на будущее, ведь в нём содержится необходимая для доступа информация.<br>
	Ты можешь изменить пароль на удобный и запоминающийся прямо сейчас 
	в «<a HREF="{{ Config('global.project.url') }}/admin/settings/" STYLE="color:#373d43; font-size:14px; 
	font-family:Arial,Sans-serif;">Персональных настройках</a>»<br>

	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">

		<TR>
			<TD style="padding-right:20px; color:#AAAAAA; font-size:14px; font-family:Arial,Sans-serif;">
				<br>Пароль
			</TD>
			<TD style="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
				<br><b>{{ $plainPassword }}</b>
			</TD>
		</TR>

		<TR>
			<TD style="padding-right:20px; color:#AAAAAA; font-size:14px; font-family:Arial,Sans-serif;">
				<br>E-mail (он же логин)
			</TD>
			<TD style="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
				<br><b><a HREF="mailto:{{ $user->email }}" STYLE="color:#373d43; font-size:14px; font-family:Arial,Sans-serif; 
					text-decoration:none;">{{ $user->email }}</a></b>
			</TD>
		</TR>

		<TR>
			<TD style="padding-right:20px; color:#AAAAAA; font-size:14px; font-family:Arial,Sans-serif;">
				<br>Адрес «Личного кабинета»
			</TD>
			<TD style="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
				<br><b><a HREF="{{ Config('global.project.url') }}/admin" STYLE="color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
					{{ Config('global.project.url') }}/admin</a></b>
			</TD>
		</TR>

	</TABLE>

@endsection