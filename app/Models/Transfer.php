<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_inventory_id',
        'to_inventory_id',
        'from_organization_id',
        'to_organization_id',
        'from_user_id',
        'to_user_id',
        'quantity',
    ];

    public function fromInventory()
    {
        return $this->belongsTo(Inventory::class, 'from_inventory_id');
    }

    public function toInventory()
    {
        return $this->belongsTo(Inventory::class, 'to_inventory_id');
    }

    public function fromOrganization()
    {
        return $this->belongsTo(Organization::class, 'from_organization_id');
    }

    public function toOrganization()
    {
        return $this->belongsTo(Organization::class, 'to_organization_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
