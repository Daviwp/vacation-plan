<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VacationPlan;
use PDF;
use Illuminate\Support\Facades\Log;

class VacationPlanController extends Controller
{
    public function index()
    {
        try {
            $plans = VacationPlan::all();
            return response()->json($plans, 200);
        } catch (\Exception $e) {
            Log::error("Error fetching vacation plans: " . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch vacation plans'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'date' => 'required|date_format:Y-m-d',
                'location' => 'required|string',
                'participants' => 'nullable|array', // Aceita array diretamente
            ]);

            $plan = VacationPlan::create($request->all());

            return response()->json($plan, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error creating vacation plan: " . $e->getMessage());
            return response()->json(['error' => 'Unable to create vacation plan', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $plan = VacationPlan::findOrFail($id);

            // Decodificar participantes se for uma string JSON
            if (is_string($plan->participants)) {
                $plan->participants = json_decode($plan->participants, true);
            }

            return response()->json($plan, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vacation plan not found'], 404);
        } catch (\Exception $e) {
            Log::error("Error fetching vacation plan: " . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch vacation plan', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $plan = VacationPlan::findOrFail($id);

            $request->validate([
                'title' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'date' => 'sometimes|required|date_format:Y-m-d',
                'location' => 'sometimes|required|string',
                'participants' => 'sometimes|nullable|array', // Aceita array diretamente
            ]);

            $plan->update($request->all());

            return response()->json($plan, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vacation plan not found'], 404);
        } catch (\Exception $e) {
            Log::error("Error updating vacation plan: " . $e->getMessage());
            return response()->json(['error' => 'Unable to update vacation plan', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $plan = VacationPlan::findOrFail($id);
            $plan->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vacation plan not found'], 404);
        } catch (\Exception $e) {
            Log::error("Error deleting vacation plan: " . $e->getMessage());
            return response()->json(['error' => 'Unable to delete vacation plan', 'message' => $e->getMessage()], 500);
        }
    }

    public function generatePdf($id)
    {
        try {
            $plan = VacationPlan::findOrFail($id);
    
            // Decodificar participantes se necessário e converter para string
            if (is_string($plan->participants)) {
                $participantsArray = json_decode($plan->participants, true);
                if (is_array($participantsArray)) {
                    $plan->participants = implode(', ', $participantsArray);
                } else {
                    $plan->participants = ''; // Se JSON não for um array válido
                }
            } elseif (is_array($plan->participants)) {
                $plan->participants = implode(', ', $plan->participants);
            } else {
                $plan->participants = '';
            }
    
            // Log para depuração
            Log::info("Formatted Participants: " . $plan->participants);
    
            // Gerar PDF
            $pdf = PDF::loadView('pdf.vacation-plan', compact('plan'));
            return $pdf->download('vacation-plan-' . $id . '.pdf');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vacation plan not found'], 404);
        } catch (\Exception $e) {
            Log::error("PDF Generation Error: " . $e->getMessage());
            return response()->json(['error' => 'Unable to generate PDF', 'message' => $e->getMessage()], 500);
        }
    }
    



}
