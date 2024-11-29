<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'role_id',
        'profile_image'
    ];

    public function roleInfo(){
        return $this->belongsTo(Role::class,'role_id','id')->select('id','role_name');
    }

    public function getProfileImageAttribute($value)
    {
        if ($value) {
            return asset('storage/profile_images/' . $value);
        }

        return null;
    }
}
