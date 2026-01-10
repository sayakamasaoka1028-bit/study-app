<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'line_user_id', // ← ★必ず入れる
    ];

    /**
     * 非表示属性
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * キャスト
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // ★ null を許可するため string キャストのみ
        'line_user_id' => 'string',
    ];
}
