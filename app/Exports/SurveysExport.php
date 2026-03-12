<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class SurveysExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Survey::with('participant')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($survey) {
                return [
                    'Participante' => $survey->participant->nombre . ' ' . $survey->participant->paterno,
                    'Email' => $survey->participant->correo,
                    'Ciudad' => $survey->participant->ciudad,
                    'P1: Experiencia General' => $survey->q1,
                    'P2: Comida y Bebidas' => $survey->q2,
                    'P3: Organización' => $survey->q3,
                    'P4: Recomendación' => $survey->q4,
                    'P5: Repetiría' => $survey->q5,
                    'Promedio' => number_format(($survey->q1 + $survey->q2 + $survey->q3 + $survey->q4 + $survey->q5) / 5, 2),
                    'Comentarios' => $survey->comentarios,
                    'Fecha' => $survey->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Participante',
            'Email',
            'Ciudad',
            'P1: Experiencia General',
            'P2: Comida y Bebidas',
            'P3: Organización',
            'P4: Recomendación',
            'P5: Repetiría',
            'Promedio',
            'Comentarios',
            'Fecha',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '002395'],
                ],
            ],
        ];
    }
}
