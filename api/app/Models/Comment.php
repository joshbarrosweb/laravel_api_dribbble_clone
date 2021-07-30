<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Likeable;

class Comment extends Model
{
    use HasFactory;
    use Likeable;

    protected $fillable = [
    	'body',
    	'user_id'
    ];

    public function commentable()
    {
    	return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
