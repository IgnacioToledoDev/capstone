<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ReservationMaintenanceMailable;
use App\Models\Car;
use App\Models\Reminder;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;

class ReservationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/jwt/reservations",
     *     summary="Crear una nueva reservación",
     *     description="Crea una nueva reservación para el vehículo especificado y opcionalmente configura un recordatorio.",
     *     tags={"Reservations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reservationDate", "carId"},
     *             @OA\Property(property="reservationDate", type="string", format="date-time", example="2024-10-15 14:30", description="Fecha y hora de la reservación en formato d-m-Y H:i"),
     *             @OA\Property(property="carId", type="integer", example=1, description="ID del vehículo para la reservación"),
     *             @OA\Property(property="wantReminder", type="boolean", example=true, description="Si el usuario desea un recordatorio"),
     *             @OA\Property(property="typeReminder", type="integer", example=2, description="Tipo de recordatorio si se solicita"),
     *             @OA\Property(property="mechanicId", type="integer", example=3, description="ID del mecánico a notificar")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservación creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="reservation", type="object"),
     *                 @OA\Property(property="reminder", type="string", example="Sera enviado a traves de correo email (Por defecto)")
     *             ),
     *             @OA\Property(property="message", type="string", example="reservation created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object", description="Detalles de los errores de validación")
     *         )
     *     )
     * )
     */
    public function createReservation(Request $request): JsonResponse
    {
        $request->validate([
            'reservationDate' => 'required|date|after:now',
            'carId' => 'required|integer'
        ]);

        $carId = $request->get('carId'); // find the car to check the owner
        $reservationDate = $request->get('reservationDate'); // this is in format d-m-Y h:m
        $wantReminder = $request->get('wantReminder');
        $typeReminder = $request->get('typeReminder');

        $owner = $this->getOwnerData($carId);
        if (empty($owner)) {
            $success['error'] = 'owner not found';
        }

        $reservation = new Reservation();
        $reservation->car_id = $carId;
        $reservation->date_reservation = $reservationDate;
        $reservation->is_approved_by_mechanic = false;
        $reservation->save();

        if ($wantReminder) {
            $reminder = new Reminder();
            $reminder->is_sending = false;
            $reminder->contact_type_id = $typeReminder;
            $reminder->save();

            $reservation->has_reminder = $wantReminder;
            $reservation->reminder_id = $reminder->id;
            $reservation->save();
        }
        $mechanicId = $request->get('mechanicId');
        $this->sendMechanicNotification($mechanicId, $reservation, $owner);

        $success['reservation'] = $reservation;
        $success['reminder'] = $wantReminder ? $reminder : 'Sera enviado a traves de correo email (Por defecto)';

        return $this->sendResponse($success, 'reservation created successfully');
    }

    private function getOwnerData($carId)
    {
        try {
            $car = Car::whereId($carId)->first();
            $ownerId = $car->owner_id;
            $owner = User::whereId($ownerId)->first();
            if(!$owner) {
                return null;
            }

            return $owner;
        } catch (ModelNotFoundException $exception) {
            $owner = null;
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    private function sendMechanicNotification($mechanicId, Reservation $reservation, User $client): void
    {
        try {
            $mechanic = User::whereId($mechanicId)->first();
            $mechanicEmail = $mechanic->email;
            Mail::to($mechanicEmail)->send(new ReservationMaintenanceMailable($mechanic, $reservation, $client));

            return;
        } catch (ModelNotFoundException $exception) {
            $mechanic = null;
            return;
        }
    }
}
