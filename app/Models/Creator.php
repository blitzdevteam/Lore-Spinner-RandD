<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Creator extends Authenticatable implements FilamentUser, HasName, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'is_active', 'created_at', 'updated_at',
    ];

    protected $appends = [
        'full_name',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->full_name;
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile()
            ->useFallbackUrl(Storage::disk('public')->url('avatar/'.str($this->id)->substr(0, 1).'.png'));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        VerifyEmail::createUrlUsing(fn ($notifiable) => URL::temporarySignedRoute(
            'user.authentication.verify.confirm',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1((string) $notifiable->getEmailForVerification()),
            ]
        ));

        $this->notify(new VerifyEmail);
    }

    /**
     * @return HasMany<$this, Story>
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function firstName(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => str($value)->lower()->ucfirst()->toString(),
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function lastName(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => str($value)->lower()->ucfirst()->toString(),
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
            get: fn (): string => $this?->getFirstMediaUrl('avatar')
        );
    }
}
