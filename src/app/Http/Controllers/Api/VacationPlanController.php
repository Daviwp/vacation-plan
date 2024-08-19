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
                'participants' => 'nullable|string'
            ], [
                'date.date_format' => 'The date must be in the format Y-m-d (e.g., 2024-08-19).'
            ]);

            if ($request->has('participants')) {
                $participants = array_map('trim', explode(',', $request->input('participants')));
                $request->merge(['participants' => json_encode($participants)]);
            }

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
        $startTime = microtime(true);

        try {
            $plan = VacationPlan::findOrFail($id);

            $request->validate([
                'title' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'date' => 'sometimes|required|date_format:Y-m-d',
                'location' => 'sometimes|required|string',
                'participants' => 'sometimes|nullable|string'
            ], [
                'date.date_format' => 'The date must be in the format Y-m-d (e.g., 2024-08-19).'
            ]);

            if ($request->has('participants')) {
                $participants = array_map('trim', explode(',', $request->input('participants')));
                $request->merge(['participants' => json_encode($participants)]);
            }

            $plan->update($request->all());

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;
            Log::info("Execution time for update: {$executionTime} seconds");

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
        $startTime = microtime(true);

        try {
            $plan = VacationPlan::findOrFail($id);

            $plan->participants = implode(', ', json_decode($plan->participants, true));

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
