<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // add subcopy to the message

        return (new MailMessage)
            ->subject('تعيين كلمة مرور جديدة')
            ->greeting('مرحباً!')
            ->line('قم بتعيين كلمة المرور الخاصة بك لحسابك في شاهين الاتحاد السعودي للطيران الشراعي من خلال الضغط على الزر أدناه')
            ->action('تعيين كلمة المرور', url(route('password.reset', [$this->token, 'email' => $notifiable->email])))
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، او لم تقم بطلب إنشاء حساب فلا داعي لاتخاذ أي إجراء آخر.')
            ->salutation('مع خالص التحيات.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
