<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Partner extends Model
{
    protected $table = 'partners';
    
    protected $fillable = [
    	'partnername',
        'country',
        'deflang',
        'active', 
    ];

    /*
    public function partnerUsers()
    {
        return $this->hasMany(User::class, 'partnerid');
    } 
     * 
     */
    
}
