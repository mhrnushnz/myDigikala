<?php
namespace App\Models;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model{
    use UploadFile;
    use SoftDeletes;
    protected $guarded = [];

    public function submit($validatedData, $productId, $photos)
    {
        $product = $this->submitToProducts($validatedData,$productId);
        $this->sumbitToSeoItems($validatedData, $product->id);
        $this->saveImages($photos, $product->id);

        foreach ($photos as $photo) {
            $path = 'product/' . $productId . '/' . $validatedData['slug'] . '-' . time();

            ProductImage::query()->create([
                'path' => $path,
                'product_id' => $product->id,
            ]);
        }
    }



    public function submitToProducts($validatedData, $productId){

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



    public function sumbitToSeoItems($validatedData,  $productId){
        SeoItem::query()->updateOrCreate([
            'type'=> 'product',
            'ref_id' => $productId ,
        ],[
            'slug' => $validatedData['slug'],
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
        ]);
    }




    public function saveImages($images, $productId ){
        foreach ($images as $image) {
            $this->UploadImageInWebpFormat($image, $productId, 100, 100, 'small');
            $this->UploadImageInWebpFormat($image, $productId, 300, 300, 'medium');
            $this->UploadImageInWebpFormat($image, $productId, 800, 800, 'large');
        }
    }



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
