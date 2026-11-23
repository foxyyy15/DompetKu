<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Support\Format;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $filterType = $request->query('type', 'all');
        $userId = Auth::id();

        $startOfMonth = $selectedMonth->copy()->startOfMonth();
        $endOfMonth = $selectedMonth->copy()->endOfMonth();

        $monthlyQuery = Transaction::query()
            ->forWorkspace($userId)
            ->whereBetween('occurred_at', [$startOfMonth, $endOfMonth]);

        $monthlyIncome = (int) (clone $monthlyQuery)
            ->where('type', TransactionType::Income)
            ->sum('amount');

        $monthlyExpense = (int) (clone $monthlyQuery)
            ->where('type', TransactionType::Expense)
            ->sum('amount');

        $totalIncomeAll = (int) Transaction::query()
            ->forWorkspace($userId)
            ->where('type', TransactionType::Income)
            ->sum('amount');

        $totalExpenseAll = (int) Transaction::query()
            ->forWorkspace($userId)
            ->where('type', TransactionType::Expense)
            ->sum('amount');

        $totalBalance = $totalIncomeAll - $totalExpenseAll;

        $listQuery = (clone $monthlyQuery)
            ->orderByDesc('occurred_at')
            ->orderByDesc('id');

        if (in_array($filterType, [TransactionType::Income->value, TransactionType::Expense->value], true)) {
            $listQuery->where('type', $filterType);
        }

        $transactions = $listQuery->get();

        $groupedTransactions = $transactions->groupBy(
            fn (Transaction $transaction): string => $transaction->occurred_at->format('Y-m-d')
        )->map(function ($items, string $dateKey): array {
            $date = Carbon::parse($dateKey);

            return [
                'date_label' => Format::fullDate($date),
                'day_label' => Format::dayName($date),
                'items' => $items,
            ];
        });

        $prevMonth = $selectedMonth->copy()->subMonth();
        $nextMonth = $selectedMonth->copy()->addMonth();

        return view('dashboard.index', [
            'selectedMonth' => $selectedMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'filterType' => $filterType,
            'totalBalance' => $totalBalance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'transactionCount' => $transactions->count(),
            'groupedTransactions' => $groupedTransactions,
            'categories' => Transaction::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'amount' => preg_replace('/\D+/', '', (string) $request->input('amount', '')),
        ]);

        $validated = $request->validate([
            'type' => ['required', Rule::in([TransactionType::Income->value, TransactionType::Expense->value])],
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'integer', 'min:1'],
            'occurred_at' => ['required', 'date'],
            'category' => ['required', 'string', Rule::in(array_keys(Transaction::categories()))],
            'description' => ['nullable', 'string', 'max:120'],
        ], [
            'title.required' => 'Judul transaksi wajib diisi.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.min' => 'Nominal harus lebih dari 0.',
            'occurred_at.required' => 'Tanggal transaksi wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
        ]);

        $meta = Transaction::metaFor($validated['category']);
        $date = Carbon::parse($validated['occurred_at'])->setTimeFromTimeString(now()->format('H:i:s'));

        Transaction::query()->create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => ($validated['description'] ?? null) ?: $meta['default_desc'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'occurred_at' => $date,
        ]);

        return redirect()
            ->route('home', ['month' => $date->format('Y-m')])
            ->with('status', 'Transaksi berhasil disimpan.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $userId = Auth::id();

        if ($transaction->user_id !== $userId) {
            abort(403);
        }

        $month = $transaction->occurred_at->format('Y-m');
        $transaction->delete();

        return redirect()
            ->route('home', ['month' => $month])
            ->with('status', 'Transaksi berhasil dihapus.');
    }

    private function resolveMonth(?string $monthParam): Carbon
    {
        if (is_string($monthParam) && preg_match('/^\d{4}-\d{2}$/', $monthParam) === 1) {
            return Carbon::createFromFormat('Y-m-d', $monthParam.'-01')->startOfMonth();
        }

        return now()->startOfMonth();
    }
}
