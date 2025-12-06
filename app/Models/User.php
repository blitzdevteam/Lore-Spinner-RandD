<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'last_active_at', 'is_active', 'created_at', 'updated_at',
    ];

    protected $appends = [
        'full_name',
        'is_profile_completed'
    ];

    /**
     * Register the media collections.
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile()
            ->useFallbackUrl(Storage::disk('public')->url('avatar/' . ($this->id % 2 === 0) + 1 . '.png'));
    }


    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'user.authentication.verify.confirm',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        $this->notify(new VerifyEmail);
    }

    /**
     * @return MorphMany<$this, Comment>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'author');
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'last_active_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function isProfileCompleted(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => ! blank($this->username)
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->first_name.' '.$this->last_name
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getFirstMediaUrl('avatar')
        );
    }

}
