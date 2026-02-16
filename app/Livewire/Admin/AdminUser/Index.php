<?php
namespace App\Livewire\Admin\AdminUser;
use App\Models\Admin;

use Hamcrest\Core\AllOf;
use Livewire\Component;
use phpseclib3\Crypt\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    public $roles = [];
    public $permissions = [];

    public $name;
    public $email;
    public $mobile;
    public $selectedRoles = [];
    public $selectedPermissions = [];


    public function mount(){
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    //سناریو پسورد ادمین اینجوریه که پسورد رو تو بک اند خودمون میسازیم و به ادمین میدیم! و ادمین باید پسوردی که
    // ما بهش دادیم رو استفاده کنه اگر نکنه هش میشه میره تو دیتابیس و دیگه قابل بازیابی نیست.
    public function submit()
    {
        $this->selectedPermissions = $this->permissions;
        $this->selectedRoles = $this->roles;

        $validatedData = $this->validate([
            'name' => 'required|exists:admins,email',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'required|regex:/^09[0-9]{9}$/|unique:admins,mobile',
            'selectedRoles' => 'required|array',
            'selectedRoles.*' => 'exists:roles,id',
            'selectedPermissions' => 'required|array',
            'selectedPermissions.*' => 'exists:permissions,id',
        ], [
            //نوشتن ارور های دیگر و سفارشی
            '*.required' => 'پر کردن فیلد الزامی است',
            'email.exists' => 'ایمیل نامعتبر است',
            //
        ]);
        $validatedData->reset();

        $password = $this->generatePassword();

        $admin = Admin::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'password' => Hash::make($password),
        ]);


        //اتصال نقش ها و دست رسی ها به ادمین با استفاده از تابع sync()
        $admin->roles()->sync($this->selectedRoles);
        $admin->permissions()->sync($this->selectedPermissions);

        session()->flush('message', 'ادمین با موفقیت اضافه شد! پسورد:' . $password);
    }



    //ساخت پسورد ادمین
    public function generatePassword(){
        $numbers = '0123456789';                         //اعداد 0 تا 9
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';             // حروف کوچک انگلیسی
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';          //حروف های بزرگ انگلیسی
        $symbols = '@!#$%^&*()_+=-[]{}><?.,';                        //سمبول ها


        //حداقل یک عددو یک حرف بزرگ و یک حرف کوچک و یک سیمبول اضافه میکنیم
        $password = [                                                //خود پسورد شامل 4 تا دیتاس
            $numbers[random_int(0, strlen($numbers) - 1)],      //هرکدوم از این 4 تا یه کاراکتر به صورت رندوم انتخاب میکنه
            $lowercase[random_int(0, strlen($lowercase) - 1)],
            $uppercase[random_int(0, strlen($uppercase) - 1)],
            $symbols[random_int(0, strlen($symbols) - 1)]
        ];


        //کاراکتر های تصادفی دیگر اضافه میکنیم.
        $allcharacters = $numbers . $lowercase . $uppercase . $symbols;
        while(count($password) < $length = 6){
            $char = $allcharacters[random_int(0, strlen($allcharacters) - 1)];

            //بررسی عدم تکرار کارکتر پشت سر هم
            if (count($password) > 0 and $password[count($password) - 1] === $char) {
                continue;
            }
            $password[] = $char;
        }


        //مخلوط کردن کاراکتر ها
        shuffle($password);
        return implode('', $password);
    }




    public function render(){
        //نیازی به تعریف ریلشن در مدل ادمین نیست چون با استفاده از پکیج پرمیشن داریم به نقش هاو دست رسی های ادمین رابطه برقرار میکنیم roles.permissions خودش دست رسی میده
        $admins = Admin::query()->with('roles.permissions')->get();
        return view('livewire.admin.admin-user.index', compact('admins'))->layout('layouts.admin.app');
    }
}
