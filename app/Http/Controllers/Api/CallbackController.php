<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

//import model Donation
use App\Models\Donation;

//import Xendit
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

//import Http Request
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        Configuration::setXenditKey(config('xendit.API_KEY'));
    }

    public function index(Request $request)
    {
        //init invoice api from xendit
        $apiInstance = new InvoiceApi();

        //get invoice by id
        $getInvoice  = $apiInstance->getInvoiceById($request->id);

        // Get data donation by external id
        $donation = Donation::where('external_id', $request->external_id)->firstOrFail();

        // Update status payment on database
        $donation->status = $getInvoice['status'];
        $donation->save();

        //return json
        return response()->json(['data' => 'Success']);
    }
}   