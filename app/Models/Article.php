<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", 'id');
    }
}
