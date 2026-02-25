<div class="row w-100 p-5">
    <form wire:submit.prevent="submit" class="col-12 w-100 d-flex justify-content-between" enctype="multipart/form-data">
        <div class="col-8">
            <div class="">


                {{--     name       --}}
                <div class="form-group mb-4">
                    <label class="" for="seller">نام محصول"</label>
                    <input wire:model.live.debunce.1500ms="name"  name="name" type="text" class="form-control"> {{--  برای لایوایر باید اینجوری بنویسیم که بفرسته به بکند و توی اینپوت اسلاگ نمایش بده --}}

                    @error('name')
                    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                        <strong>خطا!</strong>{{ $message }}
                    </div>
                    @enderror
                </div>


                {{--     slug       --}}
                <div class="form-group mb-4">
                    <label class="" for="seller">اسلاگ</label>
                    <input wire:model="slug"  name="slug" type="text" class="form-control">
                    @error('slug')
                    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                        <strong>خطا!</strong>{{ $message }}
                    </div>
                    @enderror
                </div>


                {{--     meta title       --}}
                <div class="form-group mb-4">
                    <label class="" for="seller">عنوان متا</label>
                    <input wire:model="meta_title"  name="meta_title" type="text" class="form-control">
                    @error('meta-title')
                    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                        <strong>خطا!</strong>{{ $message }}
                    </div>
                    @enderror
                </div>


                {{--     meta description        --}}
                <div class="form-group mb-4">
                    <label class="" for="seller">توضیحات متا</label>
                    <input wire:model="meta_description" name="meta_description" type="text" class="form-control">
                    @error('meta_description')
                    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                        <strong>خطا!</strong>{{ $message }}
                    </div>
                    @enderror
                </div>



            </div>

            {{--     image      --}}
            <div class=" flex-wrap ">
                <label for="img_p" >اپلود تصاویر محصول</label>
                <div
                    x-data="{ uploading: false, progress: 0 }"
                    x-on:livewire-upload-start="uploading = true"
                    x-on:livewire-upload-finish="uploading = false"
                    x-on:livewire-upload-cancel="uploading = false"
                    x-on:livewire-upload-error="uploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <br>
                    <input class="form-control" type="file" wire:model="photos" name="photos" multiple >
                    <div x-show="uploading">
                        <progress max="100" x-bind:value="progress"></progress>
                    </div>
                    @error('photos')
                    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                        <strong>خطا!</strong>{{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- نمایش تصاویره انتخاب شده در ادمین --}}
                <div class="d-flex flex-wrap">
                        @foreach($photos as $index => $photo)
                            @if(in_array($photo->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg', 'image/webp']))
                                <div class="item w-25 m-2">
                                    <img class="w-100" src="{{ $photo->temporaryUrl() }}" alt="">
                                    <div class="d-flex justify-content-between align-items-center mt-2 bg-dark p-2 rounded">
                                        <div class="form-check form-check-inline">
                                            <input wire:click="setCoverImage({{ $index }})" {{ $index == $coverIndex ? 'checked' : '' }} type="radio" name="cover_image" id="cover_image" class="form-check-input">
                                            <label for="cover_image">به عنوان کاور</label>
                                        </div>

                                        <a wire:click="removePhoto({{$index}})" href="javascript:void(0);" class="action-btn text-danger btn-delete bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="حذف">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
            </div>



        </div>


        <div class="col-4">
            {{--     price       --}}
            <div class="form-group mb-4">
                <input wire:model="price"  name="price" type="text" class="form-control" id="price" placeholder="قیمت">
                @error('price')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     seller       --}}
            <div class="form-group mb-4 flex gap-4 form-control">
                <label class="" for="sellerId">فروشنده</label>
                <select wire:model="sellerId" name="sellerId" id="seller" class="selectpicker">
                    @foreach($sellers as $seller)
                        <option value="{{ @$seller->id }}" {{$seller->id == @$product->seller_id ? 'selected' : ''}}>{{ $seller->shop_name }}</option>
                    @endforeach
                </select>

                @error('sellerId')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     category       --}}
            <div class="form-group mb-4 flex form-control">
                <label class="" for="category">دسته بندی </label>
                <select wire:ignore wire:model="categoryId" id="category" name="categoryId" class="selectpicker" placeholder="دسته بندی محصول">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" >{{$category->name}}</option>
                    @endforeach
                </select>
                @error('categoryId')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     stock       --}}
            <div class="form-group mb-4">
                <input wire:model="stock"  name="stock" type="text" class="form-control" placeholder="موجودی">
                @error('stock')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     discount       --}}
            <div class="form-group mb-4">
                <input wire:model="discount" name="discount" type="text" class="form-control"  placeholder="درصد تخفیف">
                @error('discount')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     discount duration       --}}
            <div class="form-group mb-4">
                <input wire:model="discount_duration" name="discount_duration" type="date" value="" class="form-control"  placeholder="تاریخ انقضا">
                @error('discount_duration')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>


            {{--     featured       --}}
            <div class="custom-control custom-checkbox checkbox-info">
                <input wire:model="featured" name="featured" type="checkbox" {{@$product->featured == true ? 'checked' : ''}} class="custom-control-input" id="featured">
                <label class="custom-control-label" for="featured">کالای ویژه</label>
                @error('featured')
                <div class="alert alert-danger mb-4" role="alert" wire:loading.remove>     {{--  از وایر دات لودینگ ریمو استفاده میکنیم که زمانی که دو باره ارور خواست نمایش داده شه در حد چند صدم ثانیع حذف بشه و دوباره نمایش داده شه --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
                    <strong>خطا!</strong> {{ $message }}
                </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">ثبت</button>
        </div>
    </form>
</div>
