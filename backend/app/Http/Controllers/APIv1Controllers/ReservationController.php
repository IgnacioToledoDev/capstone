<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Helper\CarHelper;
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
    private CarHelper $carHelper;

    public function __construct(CarHelper $carHelper)
    {
        $this->carHelper = $carHelper;
    }

    /**
     * @OA\Post(
     *     path="/api/jwt/reservation/create",
     *     summary="Crear una nueva reservación",
     *     description="Crea una nueva reservación para el vehículo especificado y opcionalmente configura un recordatorio.",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
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
        $reservation->is_approved_by_mechanic = null;
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
        $reservation->mechanic_id = $mechanicId;
        $this->sendMechanicNotification($mechanicId, $reservation, $owner);
        $reservation->save();

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

    /**
     * @OA\Get(
     *     path="/api/jwt/reservation/{mechanicId}/reservations",
     *     summary="Obtener todas las reservas de un mecánico",
     *     description="Devuelve una lista de todas las reservas asociadas a un mecánico específico.",
     *     operationId="getAllReservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="mechanicId",
     *         in="path",
     *         required=true,
     *         description="ID del mecánico",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reservas recuperadas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservations retrieved successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron reservas para el mecánico proporcionado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error has occurred"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error has occurred"
     *             )
     *         )
     *     )
     * )
     */
    public function getAllReservation(int $mechanicId): JsonResponse
    {
        try {
            $reservations = Reservation::where(['mechanic_id' => $mechanicId])->get();
            if ($reservations->isEmpty()) {
                throw new ModelNotFoundException();
            }

            $responseReservations = [];
            foreach ($reservations as $reservation) {
                $car = Car::whereId($reservation->car_id)->first();
                $client = User::whereId($car->owner_id)->first();
                unset($client->password);

                $formattedDateTime = $reservation->reservation_date
                    ? $reservation->reservation_date->format('Y-m-d H:i:s')
                    : null;

                $responseReservations[] = [
                    'reservation' => array_merge($reservation->toArray(), [
                        'reservation_date' => $formattedDateTime
                    ]),
                    'car' => [
                        'id' => $car->id,
                        'patent' => $car->patent,
                        'brand' => $this->carHelper->getCarBrandName($car->id),
                        'model' => $this->carHelper->getCarModelName($car->id),
                        'year' => $car->year,
                    ],
                    'client' => $client
                ];
            }

            $success['reservations'] = $responseReservations;
            return $this->sendResponse($success, 'reservations retrieved successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('An error has occurred', 404);
        } catch (\Exception $exception) {
            return $this->sendError('An error has occurred', 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/jwt/reservation/{reservationId}/approve",
     *     summary="Aprobar una reserva",
     *     description="Marca una reserva como aprobada por el mecánico.",
     *     operationId="approvedReservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="reservationId",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva a aprobar",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reserva aprobada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation approved successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la reserva",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error has occurred"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error has occurred"
     *             )
     *         )
     *     )
     * )
     */
    public function approvedReservation(int $reservationId): JsonResponse
    {
        try {
            $reservation = Reservation::where(['id' => $reservationId])->first();
            if (!$reservation) {
                throw new ModelNotFoundException();
            }

            $car = Car::whereId($reservation->car_id)->first();
            $client = User::whereId($car->owner_id)->first();
            unset($client->password);
            $reservation->is_approved_by_mechanic = true;
            $reservation->save();
            $success['response'] = [
                'reservation' => $reservation,
                'car' => [
                    'id' => $car->id,
                    'patent' => $car->patent,
                    'brand' => $this->carHelper->getCarBrandName($car->id),
                    'model' => $this->carHelper->getCarModelName($car->id),
                    'year' => $car->year,
                ],
                'client' => $client,
            ];

            return $this->sendResponse($success, 'reservation approved successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('An error has occurred', 404);
        } catch (\Exception $exception) {
            return $this->sendError('An error has occurred', 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/jwt/reservation/{reservationId}/decline",
     *     summary="Decline a reservation",
     *     description="Marks a reservation as declined by the mechanic and provides related car and client information.",
     *     operationId="declinedReservation",
     *     tags={"Reservations"},
     *     @OA\Parameter(
     *         name="reservationId",
     *         in="path",
     *         description="ID of the reservation to decline",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation declined successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation declined successfully"
     *             ),
     *             @OA\Property(
     *                 property="response",
     *                 type="object",
     *                 @OA\Property(
     *                     property="reservation",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="is_approved_by_mechanic",
     *                         type="boolean",
     *                         example=false
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-11-10T12:00:00Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-11-10T12:30:00Z"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="car",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=15
     *                     ),
     *                     @OA\Property(
     *                         property="patent",
     *                         type="string",
     *                         example="ABC-1234"
     *                     ),
     *                     @OA\Property(
     *                         property="brand",
     *                         type="string",
     *                         example="Toyota"
     *                     ),
     *                     @OA\Property(
     *                         property="model",
     *                         type="string",
     *                         example="Corolla"
     *                     ),
     *                     @OA\Property(
     *                         property="year",
     *                         type="integer",
     *                         example=2020
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="client",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=5
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="johndoe@example.com"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Reservation not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error has occurred"
     *             )
     *         )
     *     )
     * )
     */
    public function declinedReservation(int $reservationId): JsonResponse
    {
        try {
            $reservation = Reservation::find($reservationId);
            if (!$reservation) {
                throw new ModelNotFoundException();
            }

            $reservation->is_approved_by_mechanic = false;
            $reservation->save();

            $car = Car::find($reservation->car_id);
            $client = User::find($car->owner_id);

            $success['response'] = [
                'reservation' => $reservation,
                'car' => [
                    'id' => $car->id,
                    'patent' => $car->patent,
                    'brand' => $this->carHelper->getCarBrandName($car->id),
                    'model' => $this->carHelper->getCarModelName($car->id),
                    'year' => $car->year,
                ],
                'client' => $client,
            ];

            return $this->sendResponse($success, 'reservation declined successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('Reservation not found', 404);
        } catch (\Exception $exception) {
            return $this->sendError('An error has occurred', $exception->getCode());
        }
    }
}
