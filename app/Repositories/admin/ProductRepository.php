<?php
namespace App\Repositories\admin;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SeoItem;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ProductRepository implements ProductRepositoryInterface{
    use UploadFile;
    use SoftDeletes;

    public function submit($validatedData, $productId, $images, $coverIndex){
        DB::transaction(function() use($validatedData, $productId, $images, $coverIndex){
            //این دیتابیس میاد مطمئن میشه که تمامی متد هامون کار میکنن
            $product = $this->submitToProduct($validatedData, $productId);
            $this->submitToSeo($validatedData, $product->id);
            $this->submitToProductImage($images, $product->id, $coverIndex);
            $this->saveImages($images, $product->id);

        });}

    public function SubmitContent($validatedData, $productId){
        Product::query()->where('id', $productId)->update([
            'long_description' => $validatedData['long_description'],
            'short_description' => $validatedData['short_description'],
        ]);
    }

    public function submitToProductImage($images, $productId, $coverIndex){
        ProductImage::query()->where('product_id', $productId)->update(['is_cover' => false]);

        foreach ($images as $index => $image) {
            $path = $image->store("product/" . $productId . '/public');

            ProductImage::query()->create([
                'path' => $path,
                'product_id' => $productId,
                'is_cover' => $index == $coverIndex,    //زمانی چه ایندکس و کاور ایندکس برابر باشن نتیجه true میشه
            ]);
        }
    }

    public function saveImages($images, $productId ){
        foreach ($images as $image) {
            $this->UploadImageInWebpFormat($image, $productId, 100, 100, 'small');
            $this->UploadImageInWebpFormat($image, $productId, 300, 300, 'medium');
            $this->UploadImageInWebpFormat($image, $productId, 800, 800, 'large');
        }


    }

    public function submitToProduct($validatedData, $productId){

        return Product::query()->updateOrCreate([
            'id'=> $productId,
        ],[
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'discount' => $validatedData['discount'],
            'stock' => $validatedData['stock'],
            'featured' => $validatedData['featured'],
            'discount_duration' => $validatedData['discount_duration'],
            'seller_id' => $validatedData['sellerId'],
            'category_id' => $validatedData['categoryId'],
            'p_code' => config('app.name') . '-' . $this->generateProductCode(),
            //تابع کانفیگ میره نام برنامه هرچی باشه رو از فایل .env میگیره
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

    public function submitToSeo($validatedData,  $productId){
        SeoItem::query()->updateOrCreate([
            'type'=> 'product',
            'ref_id' => $productId ,
        ],[
            'slug' => $validatedData['slug'],
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
        ]);
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
}
