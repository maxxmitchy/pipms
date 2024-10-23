<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['batch_number', 'reorder_level', 'quantity', 'expiration_date', 'medication_id', 'organization_id', 'reorder_quantity'];

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('batch_number', 'like', "%{$term}%")
                ->orWhereHas('medication', function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%");
                })
                ->orWhereHas('organization', function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%");
                });
        });
    }
}
