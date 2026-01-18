<?php
// File: app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Preview Invoice (stream to browser)
     */
    public function preview(Order $order)
    {
        // Check authorization
        $this->authorizeAccess($order);

        // Only approved+ orders have invoices
        if (!$this->canAccessInvoice($order)) {
            abort(404, 'Invoice belum tersedia untuk pesanan ini.');
        }

        return $this->createInvoice($order, true);
    }

    /**
     * Download Invoice
     */
    public function download(Order $order)
    {
        // Check authorization
        $this->authorizeAccess($order);

        // Only approved+ orders have invoices
        if (!$this->canAccessInvoice($order)) {
            abort(404, 'Invoice belum tersedia untuk pesanan ini.');
        }

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
     * Check if user can access invoice
     */
    private function canAccessInvoice(Order $order): bool
    {
        // Invoice available for approved, paid, processing, shipped, completed orders
        return in_array($order->status, ['approved', 'paid', 'processing', 'shipped', 'completed']);
    }

    /**
     * Authorize access based on user role
     */
    private function authorizeAccess(Order $order): void
    {
        $user = auth()->user();

        // Owner and Admin can access all invoices
        if ($user->isOwner() || $user->isAdmin()) {
            return;
        }

        // Customer can only access their own invoices
        if ($user->isCustomer() && $order->customer_id === $user->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke invoice ini.');
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
}

