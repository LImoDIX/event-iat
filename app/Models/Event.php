<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo_path
 * @property \Illuminate\Support\Carbon $start_time
 * @property int $duration
 * @property int $max_attendees
 * @property int $organizer_id
 * @property bool $is_approved
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo_path',
        'start_time',
        'duration',
        'max_attendees',
        'organizer_id',
        'is_approved',
        'is_completed',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'is_approved' => 'boolean',
        'is_completed' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_approved' => true,
    ];

    /**
     * Get the organizer that owns the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the attendances for the event.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }
    /**
     * Get the ratings for the event.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(EventRating::class);
    }
    
    /**
     * Get the user registration status for the event.
     *
     * @return bool
     */
    public function getUserRegisteredAttribute(): bool
    {
        // Если пользователь не аутентифицирован, возвращаем false
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return false;
        }
        
        // Проверяем, зарегистрирован ли текущий пользователь на мероприятие
        return $this->registrations()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->exists();
    }
    
    /**
     * Get the user registration code for the event.
     *
     * @return string|null
     */
    public function getUserRegistrationCodeAttribute(): ?string
    {
        // Если пользователь не аутентифицирован, возвращаем null
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return null;
        }
        
        // Получаем регистрацию текущего пользователя на мероприятие
        $registration = $this->registrations()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->first();
            
        // Возвращаем код подтверждения, если регистрация существует
        return $registration ? $registration->confirmation_code : null;
    }
}
