<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolrPermissionSeeder extends Seeder
{
    public function run(): void
    {
        //دست رسی ها(permission)-------------------------------
        $permissions = [
            //مدیریت محصولات
            'view products',
            'create products',
            'edit products',
            'delete products',

            //مدیریت سفارش ها
            'view orders',
            'process orders',

            //مدیریت دسته بندی ها
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
        ];

        //ایجاد دست رسی ها در دیتابیس
        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }




        //تعریف نقش ها(role) و تشخصی دست رسی ها----------------------------------------
        //نقش ادمین کل
        $superAdmin = Role::query()->firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'admin',
        ]);
        $superAdmin->givePermissionTo(Permission::all());       //همه این پرمیشن هارو به سوپر ادمین میده




        //نقش ادمین محصولات
        $productAdmin = Role::query()->firstOrCreate([
            'name' => 'product admin',
            'guard_name' => 'admin',
        ]);
        $productAdmin->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
        ]);       //همه این پرمیشن هارو به ادمین محصولات میده




        //نقش ادمین سفارشات
        $orderAdmin = Role::query()->firstOrCreate([
            'name' => 'order admin',
            'guard_name' => 'admin',
        ]);
        $orderAdmin->givePermissionTo([
            'view orders', 'process orders']);       //همه این پرمیشن هارو به ادمین سفارشات میده





        //تعریف ادمین ها-------------------------------------------------
        //نقش ادمین کل
        $superAdminUser=Admin::query()->firstOrCreate([
            'email' => 'superadmin@example.com'            //اگر کاربری با این ایمیل وجود داشت بر میگردونه در غیزر این صورصت پایین میسازه
        ],[
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'mobile' => '09120000001'
        ]);
        $superAdminUser->assignRole('super admin');




        //نقش ادمین محصولات
        $productAdminUser=Admin::query()->firstOrCreate([
            'email' => 'productadmin@example.com'            //اگر کاربری با این ایمیل وجود داشت بر میگردونه در غیزر این صورصت پایین میسازه
        ],[
            'name' => 'product Admin',
            'password' => bcrypt('password'),
            'mobile' => '09120000002'
        ]);
        $productAdminUser->assignRole('product admin');


        //نقش ادمین سفارشات
        $orderAdminUser=Admin::query()->firstOrCreate([
            'email' => 'orderadmin@example.com'            //اگر کاربری با این ایمیل وجود داشت بر میگردونه در غیزر این صورصت پایین میسازه
        ],[
            'name' => 'order Admin',
            'password' => bcrypt('password'),
            'mobile' => '09120000003'
        ]);
        $orderAdminUser->assignRole('order admin');

    }
}
