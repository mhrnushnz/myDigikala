<?php
namespace App\Notifications;
use App\Models\User;
use App\Notification\channels\SmsChannel;
use Ghasedak\DataTransferObjects\Request\InputDTO;
use Ghasedak\DataTransferObjects\Request\ReceptorDTO;
use Ghasedaksms\GhasedaksmsLaravel\Message\GhasedaksmsVerifyLookUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

//از اینجا باید پارامتر هارو پاس بدیم به چنلی که ساختیم
class sendSmsNotification extends Notification
{
    use Queueable;

    protected $mobile;
    protected $template;
    protected $parameters;

    /**
     * Create a new notification instance.
     */
    public function __construct($mobile, $template, $parameters){
        $this->mobile = $mobile;
        $this->template = $template;
        $this->parameters = $parameters;
    }





    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array                               //چنل رو باید مشخص کنیم مثلا ایجا
//خودش به صورت پیش فرض گفته از طریق ایمیل ارسال شه ولی ما میخوایم از طریق پیامک باشه پس اسم چنلی که ساختیمو میدیم
    {
        return [SmsChannel::class];
    }





    /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable)        //اسمشو ازtoMail به toSms تغییر میدیم
    {                                              //مشخص میکنه از طریق کدوم چنل ارسال بشه
        return [
            'mobile' => $this->mobile,
            'template' => $this->template,
            'parameters' => $this->parameters,
        ];
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

