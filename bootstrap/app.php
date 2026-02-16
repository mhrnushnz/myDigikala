<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,                   //بجای استفاده از این کلاس ها از نام مستعارش استفاده میکنیم.
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);



        $middleware->redirectGuestsTo(function (){
            $currentPath = request()->path();     //تشخیص گاردز

            if(str_starts_with($currentPath, 'admin')){
                return redirect()->route('admin.auth.index');
            } elseif (str_starts_with($currentPath, 'seller')){
                return redirect()->route('admin.auth.index');
            }else{
                return route('client.auth.index');
            }

        });        //زمانی که کاربر به صورت مهمان وارد شده بره به این صفحه ورودی نام صفحه نیست همون مسیری url عه
    })



    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
