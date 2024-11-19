<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Computer;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Resources\StatsCollection;
use App\Http\Resources\ComputerResource;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ComputerCollection;
use App\Http\Resources\ReservationsCollection;
use App\Http\Requests\UpdateReservationRequest;

class ReservationController extends Controller
{
    //
    public function index()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->paginate(6);
        return new ReservationsCollection($reservations);
    }

    public function computers(){
        $computers = Computer::all();
        return new ComputerCollection($computers);
    }

    public function getComputersAvailable($start, $end){
        $start = Carbon::createFromFormat('H:i', $start)->format('H:i:s');
        $end = Carbon::createFromFormat('H:i', $end)->format('H:i:s');
        
        $computers = Computer::whereDoesntHave('reservations', function ($query) use ($start, $end) {
            $query->where(function ($query) use ($start, $end) {
                $query->where(function ($query) use ($start, $end) {
                    $query->where('start', '<', $end) // Comienza antes de que termine el rango
                          ->where('end', '>', $start); // Termina después de que comience el rango
                })->orWhere(function ($query) use ($start, $end) {
                    $query->where('start', '<=', $start) // Abarca el inicio del rango
                          ->where('end', '>=', $end); // Abarca el final del rango
                });
            });
        })->get();

        return new  ComputerCollection($computers);
    }

    public function update(UpdateReservationRequest $request , $id)
    {   
        $data = $request->validated();
        $reservation = Reservation::findOrFail($id);

        if($reservation){
            $reservation->update($data);
            return response()->json(['message' => "Reservación actualizada con exito"], 200);
        }else {
            return response()->json(['message' => "Reservación no encontrada"], 404);
        }
    }

    public function store(ReservationRequest $request)
    {
        $data = $request->validated();

        $existingReservation = Reservation::where('computer_id', $data['computer_id']) ->whereDate('start', $data['start'])->where(function ($query) use ($data) { $query->whereBetween('start', [$data['start'], $data['end']]) ->orWhereBetween('end', [$data['start'], $data['end']]) ->orWhere(function ($query) use ($data) { $query->where('start', '<=', $data['start']) ->where('end', '>=', $data['end']); }); }) ->first();
        
        if ($existingReservation) { return response()->json(['message' => 'La PC no está disponible en el rango de tiempo seleccionado'], 409); }

        Reservation::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'folio' => $data['folio'],
            'computer_id' => $data['computer_id'],
            'phone' => $data['phone'],
            'start' => $data['start'],
            'end' => $data['end'],
            'total_hours' => $data['total_hours'],
            'total_amount' => $data['total_amount'],
        ]);

        return response()->json(['message' => "Reservación creada con exito"], 201);
    }

    public function show()
    {
        $reservations = Reservation::all();
        return new StatsCollection($reservations);
    }

    public function search($word)
    {
        $reservation = Reservation::where('folio', 'like', '%' . $word . '%')->paginate(1);

        if($reservation->isNotEmpty()){
            return new ReservationsCollection($reservation);
        }else {
            return response()->json(['message' => "Reservación no encontrada"], 404);
        }
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        if($reservation){
            $reservation->delete();
            return response()->json(['message' => "Reservación eliminada con exito"], 200);
        }else {
            return response()->json(['message' => "Reservación no encontrada"], 404);
        }   
    }

    public function loanUpdate($id, Request $request){
        $reservation = Reservation::findOrFail($id);

        if($reservation){
            $data = $request->all();
            $reservation->update($data);
            return response()->json(['message' => "Reservación actualizada con exito"], 200);
        }else{
            return response()->json(['message' => "Reservación no encontrada"], 404);
        }
    }
}