<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    // GET /api/appointments
    public function index()
    {
        return response()->json(Appointment::all(), 200);
    }

    // POST /api/appointments
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'patient_name' => 'required|string|max:255',
            'doctor_name'  => 'required|string|max:255',
            'date'         => 'required|date',
            'time'         => [
                'required',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'
            ],

            'reason'       => 'nullable|string',

            'status'       => [
                'required',
                Rule::in(['pendiente', 'realizada', 'cancelada'])
            ],

            'consultorio'  => 'required|string|max:255',

        ], [
            'patient_name.required' => 'El nombre del paciente es obligatorio.',
            'doctor_name.required'  => 'El nombre del doctor es obligatorio.',
            'date.required'         => 'La Hora es obligatoria.',
            'date.date'             => 'La Fecha debe tener formato YYYY-MM-DD.',

            'time.required'         => 'El Horario es obligatorio.',
            'time.regex'            => 'El Horario debe estar en formato HH:MM (24 horas).',

            'status.required'       => 'El estado es obligatorio.',
            'status.in'             => 'El estado solo puede ser: pendiente, realizada o cancelada.',

            'consultorio.required'  => 'El consultorio es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        $appointment = Appointment::create($validator->validated());

        return response()->json([
            'message' => 'Cita creada correctamente',
            'data'    => $appointment
        ], 201);
    }

    // GET /api/appointments/{id}
    public function show($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        return response()->json($appointment, 200);
    }

    // PUT/PATCH /api/appointments/{id}
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [

            'patient_name' => 'sometimes|required|string|max:255',
            'doctor_name'  => 'sometimes|required|string|max:255',
            'date'         => 'sometimes|required|date',

            'time'         => [
                'sometimes',
                'required',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'
            ],

            'reason'       => 'nullable|string',

            'status'       => [
                'sometimes',
                'required',
                Rule::in(['pendiente', 'realizada', 'cancelada'])
            ],

            'consultorio'  => 'sometimes|required|string|max:255',

        ], [
            'patient_name.required' => 'El nombre del paciente es obligatorio.',
            'doctor_name.required'  => 'El nombre del doctor es obligatorio.',
            'date.required'         => 'La Hora es obligatoria.',
            'date.date'             => 'La Fecha debe tener formato YYYY-MM-DD.',

            'time.required'         => 'El Horario es obligatorio.',
            'time.regex'            => 'El Horario debe estar en formato HH:MM (24 horas).',

            'status.required'       => 'El estado es obligatorio.',
            'status.in'             => 'El estado solo puede ser: pendiente, realizada o cancelada.',

            'consultorio.required'  => 'El consultorio es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        $appointment->update($validator->validated());

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data'    => $appointment
        ], 200);
    }

    // DELETE /api/appointments/{id}
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        $appointment->delete();

        return response()->json(['message' => 'Cita eliminada'], 200);
    }
}
