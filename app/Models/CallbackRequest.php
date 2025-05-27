<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallbackRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_handled', // Thêm is_handled vào fillable nếu bạn muốn gán giá trị khi tạo
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'is_handled' => 'boolean',
    ];

    // Mặc định timestamps là true, không cần định nghĩa lại trừ khi bạn muốn tắt
    // public $timestamps = true;
}