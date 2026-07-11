<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'client_type',
        'company',
        'industry',
        'website',
        'tax_id',
        'email',
        'secondary_email',
        'phone',
        'secondary_phone',
        'address',
        'city',
        'country',
        'postal_code',
        'source',
        'account_manager_id',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'status' => 'string',
        'client_type' => 'string',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class)->orderByDesc('is_primary')->orderBy('name');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class)->latest();
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function getPrimaryContactAttribute(): ?ClientContact
    {
        return $this->contacts->firstWhere('is_primary', true) ?? $this->contacts->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('company', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
