<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    //
    protected $data;

    public function index(Request $request)
    {

        if (! hasPermission('payment.index')) {
            abort(403);
        }
        $this->data['pageTitle'] = 'Payments history';

        $payments = Payment::with(['bookings', 'bookings.customer']);

        // Filters
        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '<=', $toDate);
        }

        if ($request->filled('status')) {
            $payments->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {

            $payments->where('payment_method', $request->payment_method);
        }

        $this->data['payments'] = $payments->orderBy('payments.id', 'desc')->get();
        return view('admin.payment.index', $this->data);
    }

    public function export(Request $request)
    {
        $payments = Payment::with(['bookings', 'bookings.customer']);

        // Apply filters same as index
        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '<=', $toDate);
        }

        if ($request->filled('status')) {
            $payments->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {

            $payments->where('payment_method', $request->payment_method);
        }

        $payments = $payments->latest()->get();

        $response = new StreamedResponse(function () use ($payments) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'Order Number',
                'Order Date',
                'Customer Name',
                'Amount',
                'Payment Status',
                'Payment Method',
            ]);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->bookings?->booking_number,
                    Carbon::parse($payment->created_at)->format('d M Y, H:i A'),
                    $payment->bookings?->customer?->name,
                    $payment->amount,
                    ucfirst($payment->status),
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                ]);
            }

            fclose($handle);
        });

        $fileName = 'payments_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
