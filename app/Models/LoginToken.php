<?php

namespace App\Models;

use App\Jobs\SendEmail;
use App\Mail\SendMagicLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
//    use HasFactory;

    const TOKEN_EXPIRE = 120;

    protected $fillable = ['token'];

    /**
     * @var mixed
     */

    //change primary key
    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //send email for magic link via email
    public function send(array $options)
    {
        SendEmail::dispatch($this->user, new SendMagicLink($this, $options));
    }

    //check expire time of token
    public function isExpired(): bool
    {
        return $this->created_at->diffInSeconds(now()) > self::TOKEN_EXPIRE;
    }
}
