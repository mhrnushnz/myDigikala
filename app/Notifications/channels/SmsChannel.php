<?php

namespace App\Notifications\channels;
use Ghasedak\GhasedakSmsApi;
use Illuminate\Notifications\Notification;

class SmsChannel{

    // این $notifiable چیست؟ یک شئ قابل نوتیفیکیشنه که میتونه هر مدل لاراولی که قابلیتدریافت نوتیفیکیشن هارو داره باشه که اغلب همون مدل User عه جرا؟ چون تو خودم مدل یوزر از تریت نوتیفیکیشن به صورت پیشفرض استفاده شده
    //این $notification چیه؟ شئ نوتیفیکیشن که حاوی داده های نوتیفیکیشنی هست که باید ارسال بشه

    public function send($notifiable , Notification $notification){
        $message = $notification->toSms($notifiable);        //ینی انگار داده هارو به مدل یوزر میفرسته

        $api = new GhasedakSmsApi(env('GHASEDAK_SMS_API_KEY'));
        $response = $api->verify(
            $message['mobile'],
            $message['template'],
            $message['parameters']
        );


        if ($response->result->code == 200) {
            return true;
        } else{
            return false;
        }
    }
}
