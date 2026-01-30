<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appcaption extends Model
{

    protected $table = 'partnerappcaptions';

    protected $fillable = [
        'langfile',
        'placeholder',
        'foundincode',
        'foundinfile', 
        'hu',
        'en',
        'de',
    ];

    public function getTranslatedAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->$locale
            ?? $this->en
            ?? '';
    }
}
