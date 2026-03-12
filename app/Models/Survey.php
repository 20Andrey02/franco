<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id','q1','q2','q3','q4','q5','comentarios'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get average score across all questions
     */
    public function getAverageScore(): float
    {
        return ($this->q1 + $this->q2 + $this->q3 + $this->q4 + $this->q5) / 5;
    }

    /**
     * Get satisfaction level
     */
    public function getSatisfactionLevel(): string
    {
        $avg = $this->getAverageScore();
        
        if ($avg >= 4.5) return 'Excelente';
        if ($avg >= 4) return 'Muy Bueno';
        if ($avg >= 3) return 'Bueno';
        if ($avg >= 2) return 'Regular';
        return 'Malo';
    }
}
