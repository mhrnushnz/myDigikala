<?php
use App\Livewire\Admin\Category\Index as CategoryIndex;
use App\Livewire\Admin\City\Index as CityIndex;
use App\Livewire\Admin\Country\Index as CountryIndex;
use App\Livewire\Admin\Dashboard\Index as DashboardIndex;
use App\Livewire\Admin\Delivery\Index as DeliveryIndex;
use App\Livewire\Admin\Payment\Index as IndexPayment;
use App\Livewire\Admin\Product\Features as ProductFeatures;
use App\Livewire\Admin\State\Index as StateIndex;
use App\Livewire\Admin\Story\Index as StoryIndex;
use App\Livewire\Admin\slider\Index as SliderIndex;
use App\Livewire\Admin\Category\Features;
use App\Livewire\Admin\Category\FeatureValue;
use App\Livewire\Admin\Product\CkUpload;
use App\Livewire\Admin\Product\Content;
use App\Livewire\Admin\Product\Create;
use App\Livewire\Admin\Product\Index;
use App\Livewire\Admin\Order\Index as orderIndex;
use App\Livewire\Admin\Order\Details as orderDetails;
use App\Livewire\Admin\Transaction\Index as transactionIndex;
use App\Livewire\Admin\Auth\Index as authIndexAdmin;
use App\Livewire\Admin\User\Index as userIndex;
use App\Livewire\Admin\AdminUser\Index as adminUserIndex;
//-------------------------------------------------------------------
use App\Livewire\Seller\Auth\Index as authIndexSeller;
//-------------------------------------------------------------------
use App\Livewire\Client\Auth\Index as ClientIndex;
use App\Livewire\Client\Home\Home as ClientHome;
use App\Livewire\Client\Payment\Callback as PaymentCallback;
use App\Livewire\Client\Product\Index as ClientProduct;
use App\Livewire\Client\Cart\Index as CartIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Client\Shipping\Index as ShippingIndex;



//Admin routes-------------------------------------------------------------------
Route::get('/auth/admin', authIndexAdmin::class)->name('admin.auth.index')->middleware('guest:admin');       //میدل ور گست اینجا برای اینه که اگر کاربر لاگین کرد
Route::get('/auth/admin/logout', [authIndexAdmin::class, 'Logout'])->name('admin.auth.logout')->middleware('auth:admin');       //میدل ور گست اینجا برای اینه که اگر کاربر لاگین کرد

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('admin.dashboard.index');
    Route::get('/country', CountryIndex::class)->name('admin.country.index');
    Route::get('/state', StateIndex::class)->name('admin.state.index');
    Route::get('/city', CityIndex::class)->name('admin.city.index');
    Route::get('/category', CategoryIndex::class)->name('admin.category.index');
    Route::get('/features/{category?}/{categoryId}', Features::class)->name('admin.features.index');
    Route::get('/category/features_value/{categoryFeature}', FeatureValue::class)->name('admin.features_value.index');
    Route::get('/product/create', Create::class)->name('admin.product_create.index');
    Route::get('/product/index', Index::class)->name('admin.product.index');
    Route::get('/product/features/{product}', ProductFeatures::class)->name('admin.product.features');
    Route::get('/product/content/{product}', Content::class)->name('admin.product.content');
    Route::get('/product/ck-upload/{productId}', CkUpload::class)->name('admin.product.ck-upload');
    Route::get('/delivery/index', DeliveryIndex::class)->name('admin.delivery.index');
    Route::get('/payment/index', IndexPayment::class)->name('admin.payment.index');
    Route::get('/story', StoryIndex::class)->name('admin.story.index');
    Route::get('/slider', SliderIndex::class)->name('admin.slider.index');
    Route::get('/order', orderIndex::class)->name('admin.order.index');
    Route::get('/order/{order}', orderDetails::class)->name('admin.order.details');
    Route::get('/transaction', transactionIndex::class)->name('admin.transaction.index');
    Route::get('/user', userIndex::class)->name('admin.user.index');
    Route::get('/admin_user', adminUserIndex::class)->name('admin.admin_user.index');
});


//seller-------------------------------------------------
Route::get('/seller/auth', authIndexSeller::class)->name('seller.auth.index')->middleware('guest:seller');       //میدل ور گست اینجا برای اینه که اگر کاربر لاگین کرد
Route::get('/seller/auth/logout', [authIndexSeller::class, 'Logout'])->name('seller.auth.logout')->middleware('auth:seller');       //میدل ور گست اینجا برای اینه که اگر کاربر لاگین کرد



//client----------------------------------------------------------------------------------------------------------
//بعضی از صفحات مهم نیست که کاربر لاگین کرده یا نه برای همین هیچی بهشون نمیدیم
Route::get('/', ClientHome::class)->name('home');
Route::get('/client/product/{p_code?}/{slug?}', ClientProduct::class)->name('client.product.index');


//صفحه هایی که کاربر به صورت مهمان(بدون لاگین کردن) دست رسی داره
//میدلور گست (مهمان) ینی کاربر بدون لاگین کردن میتونه اون صفحه هارو ببینه و نیازی به لاگین کردنش نیست
Route::middleware('guest')->group(function () {
    Route::get('/auth', ClientIndex::class)->name('client.auth.index');         //زمانی که کاربر لاگین کرد به صورت مهمان دیگه صفحه ورود نمایش داده نشه بلکه صفحه اصلی بیاد
    Route::get('/auth/gmail', [ClientIndex::class, 'redirectToProvider'])->name('gmail');         //زمانی که کاربر لاگین کرد به صورت مهمان دیگه صفحه ورود نمایش داده نشه بلکه صفحه اصلی بیاد
    Route::get('/auth/gmail/callback', [ClientIndex::class, 'handelProviderCallback'])->name('callback');         //زمانی که کاربر لاگین کرد به صورت مهمان دیگه صفحه ورود نمایش داده نشه بلکه صفحه اصلی بیاد
});



//صفحه هایی که کاربر نیاز به لاگین کردن داره
//صفحه پرووفایل و صفحه سبد خرید اطلاعات رو بر اساس user_id برمیگردونه!برای همین بهشون middleware auth رو اعمال میکنیم ینی حتما کاربر باید لاگین کنه
// تا بتونه اون صفحه هارو ببینه و میدلور آس خودش به صورتت پیش فرض جوری تنظیم شده که بره صفحه ورود رو اجرا کنه ینی نیاز نیستت تو فایل app.php بریم کد کاستوم بزنیم
Route::middleware('auth' )->group(function () {
    Route::get('/logout', [ClientIndex::class,'clientLogout'])->name('client.auth.logout');


});

Route::get('/checkout/cart', CartIndex::class)->name('client.cart.index');          //تا زمانی که کاربر لاگین نشه نمایش داده نشه و وقتی تو url ادرس صفحه رو وارد کردیم بره تو صفحه ورود به حساب کار بری ! برای شخصی سازی و ئارد کرد ادرس صفحه ورود باید به این مسیر بریم digikala/bootstrap/app.php
Route::get('/client/shipping', ShippingIndex::class)->name('client.shipping.index');          //تا زمانی که کاربر لاگین نشه نمایش داده نشه و وقتی تو url ادرس صفحه رو وارد کردیم بره تو صفحه ورود به حساب کار بری ! برای شخصی سازی و ئارد کرد ادرس صفحه ورود باید به این مسیر بریم digikala/bootstrap/app.php
Route::get('/client/payment/callback', PaymentCallback::class)->name('client.payment.callback');          //تا زمانی که کاربر لاگین نشه نمایش داده نشه و وقتی تو url ادرس صفحه رو وارد کردیم بره تو صفحه ورود به حساب کار بری ! برای شخصی سازی و ئارد کرد ادرس صفحه ورود باید به این مسیر بریم digikala/bootstrap/app.php





//------------------------------------------------------------------------------------
//2 نکته مهم!

//کلا دو نوع میدلور داریم auth و guest ک اینا پیشفرض لاراول اند


//ب هر اندازه ای ک بخواهیم میتوانیم نقش تولید کنیم اما یک نقش پیشفرض با نام web که از
//مدل user پروایدر میکنه وجود دارد بجز آن نقش های admin - seller رو میتونیم خورمون بسازیم
