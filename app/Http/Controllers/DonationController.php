<?php

namespace App\Http\Controllers;

//import model Donation
use App\Models\Donation;

//import Xendit
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

//import Http Request
use Illuminate\Http\Request;

//import Str
use Illuminate\Support\Str;

class DonationController extends Controller
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

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data donations
        $donations = Donation::latest()->paginate(10);

        //render view
        return view('donations.index', compact('donations'));
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        //render view
        return view('donations.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'amount'    => 'required',
            'note'      => 'required',
        ]);

        //init invoice api from xendit
        $apiInstance = new InvoiceApi();

        //request create invoice
        $create_invoice = new \Xendit\Invoice\CreateInvoiceRequest([
            'external_id' => (string) Str::uuid(),
            'payer_email' => $request->email,
            'description' => $request->note,
            'amount'      => $request->amount,
            'failure_redirect_url' => config('app.url') . '/donations',
            'success_redirect_url' => config('app.url') . '/donations',
        ]);

        try {

            //create invoice
            $invoice = $apiInstance->createInvoice($create_invoice);

            //insert donation to database
            $donation = Donation::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'amount'    => $request->amount,
                'note'      => $request->note,
                'status'    => 'PENDING',
                'external_id' => $create_invoice['external_id'],
                'invoice_url' => $invoice['invoice_url'],
            ]);

            if ($donation) {
                return redirect()->route('donations.index')->with('success', 'Donation created successfully');
            }
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
}