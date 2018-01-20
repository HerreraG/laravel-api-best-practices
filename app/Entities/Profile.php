<?php

namespace App\Entities;

class Profile extends Entity
{
    protected $table = 'profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];

    /** Relationships */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_profiles');
    }
}
