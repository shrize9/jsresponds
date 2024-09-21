<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Respond extends Model
{
    use HasUuids;

    protected $table='responds';
    protected $primaryKey='id';
    public $incrementing = false;
    public $timestamps = false;    
     
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'countQuestion'
    ];
    
    protected $attributes = [
        'created' => ''
    ];    
    
    protected $hidden = ['id'];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function($user) {
            $user->created = time();
        });
    }    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created' => 'integer',
        ];
    }
    
    public static function avgAsks() {
        return DB::table("responds")->avg('countQuestion');
    }
    
    public function answers(): HasMany
    {
        return $this->hasMany(\App\Models\Answer::class,'respondId');
    }            
}
