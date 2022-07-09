<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name_en' , 'name_ar' , 'type'];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function isCheckBox(){
        if($this->type == 'checkbox')
            return true;
        else
            return false;
    }

    public function isRadio(){
        if($this->type == 'radio')
            return true;
        else
            return false;
    }
}
