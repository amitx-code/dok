<HTML>
<HEAD>
    <TITLE>{{ $subject }}</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</HEAD>
<BODY leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" STYLE="background-color:#FFFFFF; margin:0px;">
<DIV STYLE="padding:10px;">
	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" ALIGN="center" STYLE="border:1px solid #36414F;">



		<TR>
			<TD STYLE="padding:20px; background-color:#36414F; color:#b4bcc8; font-size:16px; font-family:Arial,Sans-serif;">
				{{ $subject }}
			</TD>
		</TR>



        <TR VALIGN="top">
 			<TD STYLE="padding:20px; background-color:#FFFFFF; color:#373d43; font-size:14px; font-family:Arial,Sans-serif;">
           @yield('main')
			</TD>
        </TR>



		<TR>
			<TD STYLE="padding:20px; background-color:#36414F; color:#b4bcc8; font-size:11px; font-family:Arial,Sans-serif;">
					Это письмо было отправлено на адрес <A HREF="mailto:{{ $emailTo }}" 
					style="font-size:11px; color:#b4bcc8; font-family:Arial,Sans-serif; text-decoration:none;">{{ $user->email }}</A>, 
					поскольку этот адрес являетесь пользователем 
					<A HREF="{{ Config('global.project.url') }}/admin" style="color:#b4bcc8; font-size:12px; 
					font-family:Arial,Sans-serif;">{{ Config('global.project.title2') }}</A>.<br>
					Пожалуйста, не отвечай на это сообщение, поскольку оно было отправлено автоматически.<br> 
                   		© 2019 {{ Config('global.project.title0') }}
			</TD>
		</TR>



	</TABLE>
</DIV>
</BODY>
</HTML>
