<?php

namespace App\Entities;

class UserProfile extends Entity
{
    protected $table = 'user_profiles';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'isdefault'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot','created_at','updated_at'
    ];

    /** Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile() {
        return $this->belongsTo(User::class);
    }
}
