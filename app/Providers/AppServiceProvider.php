<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Email ya "weka nenosiri jipya" kwa Kiswahili.
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);
            $url = route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('Weka nenosiri jipya · '.config('app.name'))
                ->greeting('Assalamu alaykum '.$notifiable->name.',')
                ->line('Tumepokea ombi la kuweka nenosiri jipya kwa akaunti yako.')
                ->action('Weka nenosiri jipya', $url)
                ->line('Kiungo hiki kitaisha baada ya dakika '.$minutes.'.')
                ->line('Kama hukuomba hili, puuza ujumbe huu. Nenosiri lako halitabadilika.')
                ->salutation('Wako, '.config('app.name'));
        });
    }
}
