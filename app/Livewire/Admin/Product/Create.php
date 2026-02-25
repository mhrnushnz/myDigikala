<?php
namespace App\Livewire\Admin\Product;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Seller;
use App\Traits\UploadFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component{
    use UploadFile;
    use WithFileUploads;

    public $sellers= [];
    public $categories= [];
    public $photos=[];
    public $name;
    public $slug;
    public $featured;
    public $meta_title;
    public $meta_description;
    public $price;
    public $product;
    public $discount;
    public $stock;
    public $discount_duration;
    public $categoryId;
    public $sellerId;
    public $productId;
    public $coverIndex = 0;





    public function mount(){
        $this->sellers = Seller::all();
        $this->categories = Category::all();



        if (isset($_GET['p_id'])) {
            $this->product = Product::with('seo', 'images')->findOrFail(request('p_id'));
            $this->productId = $this->product->id;
            $this->name = $this->product->name;
            $this->slug = $this->product->seo->slug ?? '';
            $this->meta_title = $this->product->seo->meta_title ?? '';
            $this->meta_description = $this->product->seo->meta_description ?? '';
            $this->price = $this->product->price;
            $this->discount = $this->product->discount;
            $this->stock = $this->product->stock;
            $this->featured = $this->product->featured;
            $this->discount_duration = $this->product->discount_duration;
            $this->sellerId = $this->product->seller_id;
            $this->categoryId = $this->product->category_id;
            $this->coverIndex = $this->product->images->search(fn($img) => $img->is_cover);
        }
    }

    public function submit(){

        //بررسی چک باکس
        if (isset($this->featured)){
            $this->featured = true;
        }else{
            $this->featured = false;
        }


//نحوه فعل و غیر فعال بودن محصول ویژه
        $validatedData =  $this->validate( [
            'photos'   => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'name' => 'required|string',
            'slug' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'price' => 'required|integer',
            'discount' => 'nullable|integer',
            'stock' => 'required|integer',
            'featured' => 'nullable|boolean',
            'discount_duration' => 'nullable|string',
            'sellerId' => 'nullable|exists:sellers,id',
            'categoryId' => 'required|exists:categories,id',
            'coverIndex' => 'required|integer',
        ],[
            //نوشتن ارور های دیگر و سفارشی
            'coverIndex.required' => 'باید یک تصویر شاخص را برای محصول انتخاب کنید',
            '*.required' => 'پر کردن فیلد الزامی است',
            '*.string' => 'فرمت نام اشتباه است',
            '*.integer'=> 'این فیلد باید از نوع عددی باشد',
            '*.min' => ' حداقل تعداد کاراکتر ها 50 می باشد',
            'categoryId.exists' => 'ذسته بندی نامعتبر است',
            'sellerId.exists' => 'فروشنده نامعتبر است',
            'photos.*.image' => 'فرمت نامعتبر است ',
            //
        ]);


        $product = new Product;                      //ساختن یک شی از مدل
        $product->submit($validatedData, $this->productId, $this->photos, $this->coverIndex); //بهتره که از مدلمون ابجکت بسازیم اونم مخصوصا زمانی که تصویر به دیتابیس میفرستیم چون create/update ومحصول واقعیه بهتره ابجکت رو بسازیم !
        $this->dispatch('success', 'عملیات با موفقیت انجام شد!');
        $this->reset();
        return redirect()->route('admin.product.index');
    }



    public function updatedName(){
        $this->slug = Str::slug($this->name, '-' , null);
    }



    public function setCoverImage($index){
        $this->coverIndex = $index;
    }


    public function removePhoto($index)
    {
        if ($index === $this->coverIndex) {
            $this->coverIndex = null;
        }
        array_splice($this->photos, $index, 1);
    }


    public function RemoveOldImage(ProductImage $productImage , $productId){
        $this->repository->RemoveOldImage($productImage, $productId);
    }


    public function setCoverOldImage($imageId){
        $this->repository->setCoverOldImage($imageId, $this->productId);
        $this->dispatch('success', 'کاور محصول ویرایش شد!');}


    public function render(){
        return view('livewire.admin.product.create')->layout('layouts.admin.app');
    }
}
