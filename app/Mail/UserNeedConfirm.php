<?php

    namespace App\Mail;

    use App\Models\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\Config;

    class UserNeedConfirm extends Mailable
    {
        use Queueable, SerializesModels;

        /**
         * The order instance.
         *
         * @var User
         */
        public $user;
        public $emailTo;
        public $subject = 'Требуется подтверждение регистрации - ';
        public $plainPassword;

        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct(User $user, $confirm_token = '')
        {
            $this->user = $user;
            $emailTo = $user->email;
            $this->confirm_token = $confirm_token;
            $this->subject .= Config('global.project.title0');
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build() {
            return $this
                ->from( Config('global.project.email') , Config('global.project.webname') )
                ->subject($this->subject)
                ->view('emails.user_needconfirm');
        }
    }