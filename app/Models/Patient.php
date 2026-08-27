<?php

namespace App\Models;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded('id')]
class Patient extends Model
{
    public function Registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
