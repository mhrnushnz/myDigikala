<?php
namespace App\Providers;
use App\Repositories\admin\AdminCategoryRepository;
use App\Repositories\admin\AdminCategoryRepositoryInterface;
use App\Repositories\admin\AdminDeliveryRepository;
use App\Repositories\admin\AdminDeliveryRepositoryInterface;
use App\Repositories\admin\AdminPaymentRepository;
use App\Repositories\admin\AdminPaymentRepositoryInterface;
use App\Repositories\admin\ProductRepository;
use App\Repositories\admin\ProductRepositoryInterface;
use App\Repositories\client\first_page\ClientFirstPageInterFace;
use App\Repositories\client\first_page\ClientFirstPageRepository;
use App\Repositories\client\product_page\ClientProductRepository;
use App\Repositories\client\product_page\ClientProductRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     */
    public function register(): void{
        $this->app->bind(AdminCategoryRepositoryInterface::class, AdminCategoryRepository::class);
        $this->app->bind(AdminDeliveryRepositoryInterface::class, AdminDeliveryRepository::class);
        $this->app->bind(AdminPaymentRepositoryInterface::class, AdminPaymentRepository::class);
        $this->app->bind(ClientFirstPageInterFace::class, ClientFirstPageRepository::class);
        $this->app->bind(ClientProductRepositoryInterface::class, ClientProductRepository::class);
    }

    /**
      Bootstrap any application services.
     */
    public function boot(): void{
        Carbon::setLocale('fa');
    }
}
