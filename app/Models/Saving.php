<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'target_amount'
    ];

    public $timestamps = false;

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('amount');
    }

    public function getCurrentAmountAttribute()
    {
    	return $this->users->sum('pivot.amount');
    }
}
