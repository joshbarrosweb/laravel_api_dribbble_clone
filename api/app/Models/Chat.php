<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Chat extends Model
{
    use HasFactory;

    public function participants()
    {
    	return $this->belongsToMany(User::class, 'participants');
    }

    public function messages()
    {
    	return $this->hasMany(Message::class);
    }

    public function getLatestMessageAttribute()
    {
    	return $this->messages()->latest()->first();
    }

    public function isUnreadForUser($userId)
    {
    	return (bool)$this->messages()
    			->whereNull('last_read')
    			->where('user_id', '<>', $userId)
    			->count();
    }

    public function markAsReadForUser($userId)
    {
    	return $this->messages()
    			->whereNull('last_read')
    			->where('user_id', '<>', $userId)
    			->update([
    				'last_read' => Carbon::now()
    			]);
    }
}
