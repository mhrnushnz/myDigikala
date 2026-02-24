<?php
namespace App\Models;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Product extends Model{
    use UploadFile;
    use SoftDeletes;
    protected $guarded = [];


     public function submit($validatedData, $productId, $images, $coverIndex){
         //این دیتابیس میاد مطمئن میشه که تمامی متد هامون کار میکنن برای اطمینان حاصل کردنه
         DB::transaction(function() use($validatedData, $productId, $images, $coverIndex){
            $product = $this->submitToProducts($validatedData, $productId);
            $this->submitToSeoItems($validatedData, $product->id);
            $this->submitToProductImage($images, $product->id, $coverIndex);
            $this->saveImages($images, $product->id);
        });
     }



    public function submitToProducts($validatedData, $productId){
        return Product::query()->updateOrCreate([
            'id'=> $productId,
        ],[
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'discount' => $validatedData['discount'],
            'featured' => $validatedData['featured'],
            'discount_duration' => $validatedData['discount_duration'],
            'seller_id' => $validatedData['sellerId'],
            'category_id' => $validatedData['categoryId'],
            'p_code' => config('app.name') . '-' . $this->generateProductCode(),
            //تابع کانفیگ میره نام برنامه هرچی باشه رو از فایل .env میگیره
        ]);
    }



    public function submitToSeoItems($validatedData,  $productId){
        SeoItem::query()->updateOrCreate([
            'type'=> 'product',
            'ref_id' => $productId ,
        ],[
            'slug' => $validatedData['slug'],
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
        ]);
    }



    public function submitToProductImage($photos, $productId, $coverIndex){
        ProductImage::query()->where('product_id', $productId)->update(['is_cover' => false]);

        foreach ($photos as $index => $photo) {
            $photoName = pathinfo($photo->hashName(), PATHINFO_FILENAME). '.webp';     //نام فایل تصویر

            ProductImage::query()->create([
                'path' => $photoName,
                'product_id' => $productId,
                'is_cover' => $index == $coverIndex,    //زمانی چه ایندکس و کاور ایندکس برابر باشن نتیجه true میشه
            ]);
        }
    }



    public function saveImages($photos, $productId ){
        foreach ($photos as $photo) {
            $this->resizeImages($photo, $productId, 100, 100, 'small');
            $this->resizeImages($photo, $productId, 300, 300, 'medium');
            $this->resizeImages($photo, $productId, 800, 800, 'large');
        }
    }




    //استفاده از پکیج intervention
    public function resizeImages($photos, $productId, $width, $height, $folder){
        //ساخت دایرکتوری
        $path = public_path('products/' . $productId . '/' . $folder );        //داره میگه تو فولدر public فولدر product و این مسیرو بسازه
        if (!file_exists($path)) {
            mkdir($path, 0777, true);                             //true برای اینه اگر فولدر های قبلی ساخته نشده بود اونارم بسازه
        }


        //قرار دادن تصاویر از storage در دایرکتوری مد نظر public
        $manager = new ImageManager(new Driver());
        $manager->read($photos->getRealPath()                      //گرفتن تصاویر از storage
        ->scale($width, $height)->toWebp()->save()         //ذخیره با این ابعاد و webp باشه چون حجم کمتری داره
        ->path($path . '/' . pathinfo($photos->hashName(), PATHINFO_FILENAME). '.webp' ));
    }


    public function SubmitContent($validatedData, $productId){
        Product::query()->where('id', $productId)->update([
            'long_description' => $validatedData['long_description'],
            'short_description' => $validatedData['short_description'],
        ]);
    }


    public function generateProductCode(){
        do{
            $random_code = rand(100, 10000000);
            $check_code = Product::query()->where('p_code', $random_code)->first();
        }
        while($check_code);
        return $random_code;
    }






    public function RemoveOldImage(ProductImage $productImage , $productId){
        $product = Product::with('Images')->findOrFail($productId);
        $image = $product->Images()->where('id', $productImage->id)->first();
        if ($image) {
            $sizes = ['small', 'medium', 'large'];
            foreach ($sizes as $size) {
                $path = public_path("product/" . $product->id . '/' . $size . '/' . $image->path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $image->delete();
        }
    }

    public function setCoverOldImage($imageId, $productId){
        ProductImage::query()->where('product_id', $productId)->update(['is_cover' => false]);
        ProductImage::query()->where(['product_id' => $productId, 'id' => $imageId])->update(['is_cover' => true]);
    }

    public function removeProduct(Product $product){
        DB::transaction(function() use($product){
            $product->delete();                                                         //حذف خوده محصول
            ProductImage::query()->where('product_id', $product->id)->delete(); //حذف تصویر محصول
            SeoItem::query()->where('ref_id', $product->id)->delete();        //حذف سئو های محصول
            File::deleteDirectory('product/' . $product->id);    //حذف محصول از فایل phpstorm
        });
    }


//relations---------------------------------------------------------------------------------------
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function seo(){
        return $this->hasOne(SeoItem::class, 'ref_id', 'id')->where('type', 'product');
    }

    public function coverImage(){      //یک تصویره
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->where('is_cover', '=', true);
    }

    public function Images(){         //چندین تصویره
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function seller(){
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
