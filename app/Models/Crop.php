<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A crop
 * @property string $name
 */
class Crop extends Model
{
    use HasFactory;

    public function samples(){
        return $this->hasMany(Sample::class);
    }
}
