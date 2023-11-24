<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * An organism
 * @property string $genus
 * @property string $species
 */
class Organism extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['genus', 'species'];

    public function samples(){
        return $this->belongsToMany(Sample::class, 'abundances');
    }
}
