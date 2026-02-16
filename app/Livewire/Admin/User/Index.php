<?php

namespace App\Livewire\Admin\User;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $search= '';
    public function render()
    {
        //تابع withcount به صورت خودکار تعداد رو میده درونشم یه تابع ریلیشن میدیم درواقع تعداد اون ریلیشن رو میده
        $users = User::query()->withCount('order')
            ->latest()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('mobile', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->get();




        return view('livewire.admin.user.index', compact('users'))->layout('layouts.admin.app');
    }
}
