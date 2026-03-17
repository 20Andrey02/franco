<?php
/*
|--------------------------------------------------------------------------
| Exportación: SurveysExport
|--------------------------------------------------------------------------
| Genera un archivo Excel (.xlsx) con todas las encuestas de satisfacción.
| Usa la librería Maatwebsite/Excel (composer require maatwebsite/excel).
|
| IMPLEMENTA 3 INTERFACES:
|   - FromCollection: define QUÉ datos se exportan (collection())
|   - WithHeadings: define los ENCABEZADOS de las columnas (headings())
|   - WithStyles: define los ESTILOS visuales del Excel (styles())
|
| SE USA DESDE: SurveyController@export
|   return Excel::download(new SurveysExport, 'encuestas.xlsx');
|
| El Excel tiene una fila de encabezados azul (#002395 = azul de la bandera
| francesa) con texto blanco, y debajo todas las encuestas con datos
| del participante, calificaciones q1-q5, promedio y comentarios.
|
| NO OLVIDAR: Si se agregan más preguntas a la encuesta, actualizar
|   tanto collection() como headings() para que coincidan.
|--------------------------------------------------------------------------
*/

namespace App\Exports;

use App\Models\Survey;
// Interfaces de Maatwebsite/Excel que definen el comportamiento de la exportación
use Maatwebsite\Excel\Concerns\FromCollection;   // Exportar desde una colección
use Maatwebsite\Excel\Concerns\WithHeadings;      // Agregar encabezados
use Maatwebsite\Excel\Concerns\WithStyles;        // Dar estilo al Excel
// Clases de PhpSpreadsheet para formatear el Excel
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;          // Relleno de celdas
use PhpOffice\PhpSpreadsheet\Style\Font;          // Fuente del texto

class SurveysExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Definir QUÉ datos se exportan al Excel.
     * Retorna una colección donde cada fila es una encuesta con datos del participante.
     */
    public function collection()
    {
        return Survey::with('participant')              // Eager load del participante
            ->orderBy('created_at', 'desc')             // Más recientes primero
            ->get()
            ->map(function ($survey) {                  // Transformar cada encuesta en una fila
                return [
                    // Datos del participante (accedidos vía la relación)
                    'Participante' => $survey->participant->nombre . ' ' . $survey->participant->paterno,
                    'Email' => $survey->participant->correo,     // Campo 'correo', no 'email'
                    'Ciudad' => $survey->participant->ciudad,
                    // Las 5 preguntas Likert (1-5)
                    'P1: Experiencia General' => $survey->q1,
                    'P2: Comida y Bebidas' => $survey->q2,
                    'P3: Organización' => $survey->q3,
                    'P4: Recomendación' => $survey->q4,
                    'P5: Repetiría' => $survey->q5,
                    // Calcular promedio de las 5 respuestas (2 decimales)
                    'Promedio' => number_format(($survey->q1 + $survey->q2 + $survey->q3 + $survey->q4 + $survey->q5) / 5, 2),
                    'Comentarios' => $survey->comentarios,
                    // Formatear fecha a día/mes/año hora:minuto
                    'Fecha' => $survey->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    /**
     * Definir los encabezados (primera fila del Excel).
     * DEBEN coincidir con las llaves del array en collection().
     */
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

    /**
     * Dar estilo al Excel.
     * Fila 1 = encabezados con fondo azul francés y texto blanco en negrita.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // La clave '1' se refiere a la fila 1 (encabezados)
            1 => [
                'font' => [
                    'bold' => true,                         // Texto en negrita
                    'color' => ['rgb' => 'FFFFFF'],         // Color del texto: blanco
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,         // Relleno sólido (no degradado)
                    'startColor' => ['rgb' => '002395'],    // Azul de la bandera francesa
                ],
            ],
        ];
    }
}
