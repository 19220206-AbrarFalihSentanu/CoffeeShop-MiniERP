<?php
// File: app/Http/Controllers/Owner/InvoiceController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Generate Invoice PDF
     */
    public function generate(Order $order)
    {
        // Check if invoice already exists
        $invoicePath = "invoices/{$order->order_number}.pdf";

        if (Storage::disk('public')->exists($invoicePath)) {
            // Return existing invoice path
            return $invoicePath;
        }

        // Generate new invoice and save
        return $this->createInvoice($order, false);
    }

    /**
     * Preview Invoice (stream to browser)
     */
    public function preview(Order $order)
    {
        return $this->createInvoice($order, true);
    }

    /**
     * Download Invoice
     */
    public function download(Order $order)
    {
        $invoicePath = "invoices/{$order->order_number}.pdf";

        if (!Storage::disk('public')->exists($invoicePath)) {
            // Generate if not exists
            $this->createInvoice($order, false);
        }

        return Storage::disk('public')->download(
            $invoicePath,
            "Invoice-{$order->order_number}.pdf"
        );
    }

    /**
     * Regenerate Invoice (force)
     */
    public function regenerate(Order $order)
    {
        $invoicePath = "invoices/{$order->order_number}.pdf";

        // Delete old invoice if exists
        if (Storage::disk('public')->exists($invoicePath)) {
            Storage::disk('public')->delete($invoicePath);
        }

        $this->createInvoice($order, false);

        return redirect()->back()->with('success', 'Invoice berhasil di-regenerate!');
    }

    /**
     * Create Invoice PDF using mPDF
     */
    private function createInvoice(Order $order, bool $preview = false)
    {
        // Load relationships
        $order->load(['customer', 'items.product', 'approver']);

        // Prepare data
        $data = [
            'order' => $order,
            'company' => [
                'name' => setting('company_name', 'Eureka Kopi'),
                'email' => setting('company_email', 'info@eurekakopi.com'),
                'phone' => setting('company_phone', '081234567890'),
                'address' => setting('company_address', 'Jakarta, Indonesia'),
                'logo' => setting('company_logo'),
            ],
            'invoice_date' => $order->approved_at ?? now(),
            'tax_rate' => setting('tax_rate', 11),
        ];

        // Render HTML from blade template
        $html = view('owner.invoices.template', $data)->render();

        // Create mPDF instance
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'tempDir' => storage_path('app/temp'),
        ]);

        $mpdf->SetTitle("Invoice - {$order->order_number}");
        $mpdf->SetAuthor(setting('company_name', 'Eureka Kopi'));
        $mpdf->WriteHTML($html);

        if ($preview) {
            // Stream to browser
            return response($mpdf->Output("Invoice-{$order->order_number}.pdf", 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Invoice-' . $order->order_number . '.pdf"',
            ]);
        } else {
            // Save to storage
            $invoicePath = "invoices/{$order->order_number}.pdf";

            // Ensure directory exists
            Storage::disk('public')->makeDirectory('invoices');

            // Save PDF
            Storage::disk('public')->put($invoicePath, $mpdf->Output('', 'S'));

            return $invoicePath;
        }
    }

    /**
     * Check if invoice exists
     */
    public function exists(Order $order)
    {
        $invoicePath = "invoices/{$order->order_number}.pdf";
        return response()->json([
            'exists' => Storage::disk('public')->exists($invoicePath),
            'path' => $invoicePath,
            'url' => Storage::disk('public')->exists($invoicePath)
                ? Storage::disk('public')->url($invoicePath)
                : null,
        ]);
    }
}
