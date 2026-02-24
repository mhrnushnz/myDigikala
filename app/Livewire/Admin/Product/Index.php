<?php
namespace App\Livewire\Admin\Product;
use App\Models\Product;
use Livewire\Component;
class Index extends Component{

    public function delete(Product $product){
        $this->repository->removeProduct($product);
    }


    public function render(){
        $products = Product::query()->with('category', 'coverImage')->latest()->paginate(10);
        return view('livewire.admin.product.index', compact('products'))->layout('layouts.admin.app');
    }
}
