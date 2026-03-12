<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Participant;
use App\Exports\SurveysExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SurveyController extends Controller
{
    /**
     * Show survey form to participant
     */
    public function show(Request $request)
    {
        $code = $request->query('code');
        
        if (!$code) {
            return redirect()->route('home')->with('error', 'Código de participante no proporcionado.');
        }

        $participant = Participant::where('qr_code', $code)->first();
        
        if (!$participant) {
            return redirect()->route('home')->with('error', 'Participante no encontrado.');
        }

        // Check if survey already exists
        $existingSurvey = Survey::where('participant_id', $participant->id)->first();
        if ($existingSurvey) {
            return redirect()->route('home')
                ->with('info', 'Este participante ya ha completado la encuesta.');
        }

        return view('surveys.show', compact('participant'));
    }

    /**
     * Store survey response
     */
    public function store(Request $request)
    {
        $participant_id = $request->input('participant_id');
        
        $data = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'q1' => 'required|integer|between:1,5',
            'q2' => 'required|integer|between:1,5',
            'q3' => 'required|integer|between:1,5',
            'q4' => 'required|integer|between:1,5',
            'q5' => 'required|integer|between:1,5',
            'comentarios' => 'nullable|string|max:500',
        ]);

        // Check if survey already exists
        if (Survey::where('participant_id', $participant_id)->exists()) {
            return back()->with('error', 'Esta encuesta ya fue registrada.');
        }

        Survey::create($data);

        return redirect()->route('home')
            ->with('success', '¡Gracias por llenar la encuesta!');
    }

    /**
     * Show survey reports (admin only)
     */
    public function reports()
    {
        $totalSurveys = Survey::count();
        $totalParticipants = Participant::count();
        
        $averages = [
            'q1' => Survey::avg('q1'),
            'q2' => Survey::avg('q2'),
            'q3' => Survey::avg('q3'),
            'q4' => Survey::avg('q4'),
            'q5' => Survey::avg('q5'),
        ];

        $surveys = Survey::with('participant')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $questions = [
            'q1' => '¿Qué tal fue tu experiencia en el evento?',
            'q2' => '¿Disfrutaste de la comida y bebidas?',
            'q3' => '¿Los stands estaban bien organizados?',
            'q4' => '¿Recomendarías este evento a otros?',
            'q5' => '¿Volverías a un evento similar?',
        ];

        return view('surveys.reports', compact('totalSurveys', 'totalParticipants', 'averages', 'surveys', 'questions'));
    }

    /**
     * Export surveys to Excel
     */
    public function exportExcel()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        return Excel::download(new SurveysExport, "reportes_encuestas_{$timestamp}.xlsx");
    }

    /**
     * Export surveys to PDF
     */
    public function exportPdf()
    {
        $totalSurveys = Survey::count();
        $totalParticipants = Participant::count();
        
        $averages = [
            'q1' => Survey::avg('q1'),
            'q2' => Survey::avg('q2'),
            'q3' => Survey::avg('q3'),
            'q4' => Survey::avg('q4'),
            'q5' => Survey::avg('q5'),
        ];

        $surveys = Survey::with('participant')
            ->orderBy('created_at', 'desc')
            ->get();

        $questions = [
            'q1' => 'Experiencia general',
            'q2' => 'Comida y bebidas',
            'q3' => 'Organización',
            'q4' => 'Recomendación',
            'q5' => 'Repetiría',
        ];

        $pdf = Pdf::loadView('surveys.pdf', compact('totalSurveys', 'totalParticipants', 'averages', 'surveys', 'questions'));
        
        return $pdf->download('reportes_encuestas_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
