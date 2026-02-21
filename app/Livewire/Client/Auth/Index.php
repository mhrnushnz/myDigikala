<?php
namespace App\Livewire\Client\Auth;
use App\Models\User;
use App\Notification\channels\SmsChannel;
use App\Notifications\sendSmsNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;


class Index extends Component{
    public $mobile;


    public function redirectToProvider(){
        return Socialite::driver('google')->redirect();
    }



    public function handelProviderCallback(){
        $gmailUser = Socialite::driver('google')->stateless()->user();
        $this->checkUser($gmailUser);

        return redirect()->route('client.auth.index');
    }


    public function checkUser($gmailUser){
        $existUser = User::query()->where('email', $gmailUser['email'])->first();

        if(!$existUser){
            $newUser = User::query()->updateOrCreate([
                'email' => $gmailUser['email'],
                ],[
                'name' => $gmailUser['name'],
                ]);

            Auth::login($newUser, true);          //این true برای اینه که اگر کاربر لاگین کرد اطلاعاتش ذخیره شه و دوباره لاگین نکنه
        } else{

            Auth::login($existUser, true);
        }
    }

    public function sendSms(){
        $validator = $this->validate([
            'mobile' => ['required','regex:/^09\d{9}$/']
        ],[
            'required' => 'شماره موبایل الزامی است',
            'regex' => 'شماره موبایل نامعتبراست'
        ]);

        $this->reset('mobile');

        $activeCode = mt_rand(1000, 10000);

        //ارسال نوتیفیکیشن به کاربر
        Notification::route(SmsChannel::class, 'sms')->notify(new sendSmsNotification($validator['mobile'], 'Ghasedak', $activeCode ));
    }




    public function clientLogout(){
        session()->flush();                            //1.حذف سشن
        Auth::logout(); //2.خروج کاربر از حساب کاربری
        return redirect()->route('home');    //3.رفتن به صفحه مورد نظر
    }



    public function render(){
        return view('livewire.client.auth.index')->layout('layouts.client.auth');
    }
}
