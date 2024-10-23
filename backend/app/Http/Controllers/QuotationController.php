<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Service;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $carId = $request->get('carId');
            $userServices = $request->get('services');
            $status = $request->get('status');
            $approvedDateClient = $request->get('approvedDateClient');
            $defaultStatus = 0;
            if ($status || !empty($approvedDateClient)) {
                $defaultStatus = 1;
            }
            $quotation = new Quotation();
            $quotation->amount_services = count($userServices);
            $quotation->status = $defaultStatus;
            $quotation->approve_date_client = $approvedDateClient ?? null;
            $quotation->car_id = $carId;
            $quotation->save();
            $totalPrice = 0;
            foreach ($userServices as $userService) {
                $isApproved = $userService['isApproved'];
                if(!$quotation->exists) {
                    throw new InvalidArgumentException('Quotation no created');
                }

                $service = Service::whereId($userService['serviceId'])->first();
                if (!$service) {
                    throw new InvalidArgumentException('Service not found');
                }

                $totalPrice = $totalPrice + $service->price;
                $detail = new QuotationDetails();
                $detail->quotation_id = $quotation->id;
                $detail->service_id = $service->id;
                $detail->is_approved_by_client = $isApproved;
            }

            $quotation->total_price = $totalPrice;
            $quotation->save();
            $success['quotation'] = $quotation;
            $success['message'] = "Quotation created";
            $success['details'] = $detail;

            return $this->sendResponse($success, $success['message']);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuotationController $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationController $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotationController $quotation)
    {
        //
    }
}
