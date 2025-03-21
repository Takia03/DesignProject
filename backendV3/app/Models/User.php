<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Returns boolean value if user is admin
     * 
     * @return bool
     */
    public function isAdmin()
    {
        $admin = Admin::where('user_id', $this->id)->first();
        return $admin ? true : false;
    }


    /**
     * Make user an admin
     */
    public function makeAdmin(){
        Admin::create([
            'user_id' => $this->id
        ]);

    }



    public function problem(){
        return $this->hasMany(Problem::class)
                    ->where('status', 'published');
    }


    public function blog(){
        return $this->hasMany(Blog::class)
                    ->withCount(['votes', 'comments', 'upVotes']);
    }

    public function shortBlog(){
        return $this->hasMany(Blog::class)
                    ->select(['id', 'user_id', 'title', 'created_at', 'category'])
                    ->withCount(['votes', 'comments', 'upVotes'])
                    ->orderBy('created_at', 'desc');
    }


    public function submission(){
        return $this->hasMany(Submission::class)
                    ->with('problem:id,title,xp')
                    ->select(['id', 'user_id', 'problem_id','contest_id', 'xp', 'penalty', 'created_at']);
    }


    public function participatedContest(){
        return $this->hasMany(ContestParticipant::class)->with('contest:id,title');
    }
}
