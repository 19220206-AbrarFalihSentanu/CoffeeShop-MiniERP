<?php
// File: app/Http/Controllers/Owner/FinancialController.php
// Jalankan: php artisan make:controller Owner/FinancialController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FinancialLog;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\ManualExpenseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Financial Dashboard dengan grafik dan statistik
     */
    public function dashboard(Request $request)
    {
        // Get date range (default: current month)
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        // Summary Statistics
        $stats = [
            'total_income' => FinancialLog::income()
                ->byDateRange($startDate, $endDate)
                ->sum('amount'),

            'total_expense' => FinancialLog::expense()
                ->byDateRange($startDate, $endDate)
                ->sum('amount'),

            'net_profit' => 0, // Will calculate below

            'total_orders' => Order::whereBetween('approved_at', [$startDate, $endDate])
                ->whereIn('status', ['approved', 'paid', 'processing', 'shipped', 'completed'])
                ->count(),
        ];

        $stats['net_profit'] = $stats['total_income'] - $stats['total_expense'];

        // Monthly Trend (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $income = FinancialLog::income()
                ->byDateRange($monthStart, $monthEnd)
                ->sum('amount');

            $expense = FinancialLog::expense()
                ->byDateRange($monthStart, $monthEnd)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense,
            ];
        }

        // Income by Category
        $incomeByCategory = FinancialLog::income()
            ->byDateRange($startDate, $endDate)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Expense by Category
        $expenseByCategory = FinancialLog::expense()
            ->byDateRange($startDate, $endDate)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Top 5 Best Selling Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.approved_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['approved', 'paid', 'processing', 'shipped', 'completed'])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('owner.financial.dashboard', compact(
            'stats',
            'monthlyData',
            'incomeByCategory',
            'expenseByCategory',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Financial Logs Listing (All Activities)
     */
    public function index(Request $request)
    {
        $query = FinancialLog::with(['creator', 'reference'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20);

        // Calculate totals for current filter
        $totals = [
            'income' => FinancialLog::income()
                ->when($request->filled('start_date'), fn($q) =>
                $q->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->filled('end_date'), fn($q) =>
                $q->whereDate('transaction_date', '<=', $request->end_date))
                ->sum('amount'),

            'expense' => FinancialLog::expense()
                ->when($request->filled('start_date'), fn($q) =>
                $q->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->filled('end_date'), fn($q) =>
                $q->whereDate('transaction_date', '<=', $request->end_date))
                ->sum('amount'),
        ];

        $totals['net'] = $totals['income'] - $totals['expense'];

        // Get unique categories for filter
        $categories = FinancialLog::select('category')
            ->distinct()
            ->pluck('category');

        return view('owner.financial.index', compact('logs', 'totals', 'categories'));
    }

    /**
     * Show form for manual expense entry
     */
    public function createExpense()
    {
        return view('owner.financial.manual-expense');
    }

    /**
     * Store manual expense
     */
    public function storeExpense(ManualExpenseRequest $request)
    {
        try {
            FinancialLog::create([
                'type' => 'expense',
                'category' => $request->category,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => auth()->id(),
                'transaction_date' => $request->transaction_date,
            ]);

            return redirect()->route('owner.financial.index')
                ->with('success', 'Pengeluaran berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal mencatat pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Delete financial log (only manual expenses)
     */
    public function destroy(FinancialLog $financialLog)
    {
        // Only allow deletion of manual expenses (no reference)
        if ($financialLog->reference_type !== null) {
            return back()->with('error', 'Tidak dapat menghapus log yang berasal dari transaksi otomatis.');
        }

        try {
            $financialLog->delete();
            return back()->with('success', 'Log finansial berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }
}
