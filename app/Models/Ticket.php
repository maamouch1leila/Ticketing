<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'N_ticket',
        'probleme_declare',
        'commentaires',
        'date_de_qualification',
        'qualifie_par',
        'type_probleme',
        'type_materiel',
        'marque',
        'priorite',
        'probleme_rencontre',
        'date_de_reparation',
        'repare_par',
        'lieu_de_reparation',
        'date_de_cloture',
        'travaux_effectues',
        'image_path',
        'user_id',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Temporarily disable the observer to prevent recursion
            static::withoutEvents(function () use ($model) {
                // Save the model to get the ID
                $model->save();

                // Update the model with the N_ticket value
                $model->update([
                    'N_ticket' => 'TRD-' . date('Y') . '-' . date('m') . $model->id,
                ]);
            });

            return false; // Prevent the original save call
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technicien_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Technicien');
            });
    }

  
}
