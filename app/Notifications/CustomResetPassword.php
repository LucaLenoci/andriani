<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    /**
     * Crea l'email di reset password personalizzata.
     */
    public function toMail($notifiable)
    {
        $url = url(config('app.url') . route('password.reset', $this->token, false));

        return (new MailMessage)
            ->subject('Reset della tua password - Nome Azienda')
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }
}
