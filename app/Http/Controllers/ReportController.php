<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Support\Format;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type') === TransactionType::Income->value
            ? TransactionType::Income
            : TransactionType::Expense;

        $endMonth = $this->resolveMonth($request->query('month'));
        $startMonth = $endMonth->copy()->subMonths(5)->startOfMonth();
        $userId = Auth::id();

        $transactions = Transaction::query()
            ->forWorkspace($userId)
            ->whereBetween('occurred_at', [$startMonth, $endMonth->copy()->endOfMonth()])
            ->get();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $endMonth->copy()->subMonths($i);
            $key = $m->format('Y-m');
            $monthItems = $transactions->filter(
                fn (Transaction $t): bool => $t->occurred_at->format('Y-m') === $key
            );

            $income = (int) $monthItems->where('type', TransactionType::Income)->sum('amount');
            $expense = (int) $monthItems->where('type', TransactionType::Expense)->sum('amount');
            $value = $type === TransactionType::Income ? $income : $expense;

            $months[] = [
                'key' => $key,
                'short' => Format::shortMonth($m),
                'label' => Format::monthYear($m),
                'income' => $income,
                'expense' => $expense,
                'value' => $value,
                'is_active' => $i === 0,
            ];
        }

        $totalIncome = array_sum(array_column($months, 'income'));
        $totalExpense = array_sum(array_column($months, 'expense'));
        $netBalance = $totalIncome - $totalExpense;

        $values = array_column($months, 'value');
        $averageValue = count($values) > 0 ? (int) round(array_sum($values) / count($values)) : 0;
        $maxVal = max(1, ...$values);
        $yMax = (int) (ceil($maxVal / 200_000) * 200_000);
        if ($yMax < 1_000_000) {
            $yMax = 1_000_000;
        }

        $rangeLabel = Format::shortMonth($startMonth).' – '.Format::shortMonthYear($endMonth);
        $shortMonthsList = implode(', ', array_column($months, 'short')).' '.$endMonth->year;

        return view('reports.index', [
            'selectedMonth' => $endMonth,
            'startMonth' => $startMonth,
            'type' => $type,
            'months' => $months,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'averageValue' => $averageValue,
            'yMax' => $yMax,
            'rangeLabel' => $rangeLabel,
            'shortMonthsList' => $shortMonthsList,
        ]);
    }

    private function resolveMonth(?string $monthParam): Carbon
    {
        if (is_string($monthParam) && preg_match('/^\d{4}-\d{2}$/', $monthParam) === 1) {
            return Carbon::createFromFormat('Y-m-d', $monthParam.'-01')->startOfMonth();
        }

        return now()->startOfMonth();
    }
}
