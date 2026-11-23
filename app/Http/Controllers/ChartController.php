<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Support\Format;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChartController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type') === TransactionType::Income->value
            ? TransactionType::Income
            : TransactionType::Expense;

        $selectedMonth = $this->resolveMonth($request->query('month'));
        $userId = Auth::id();

        $startOfMonth = $selectedMonth->copy()->startOfMonth();
        $endOfMonth = $selectedMonth->copy()->endOfMonth();

        $monthlyTransactions = Transaction::query()
            ->forWorkspace($userId)
            ->where('type', $type)
            ->whereBetween('occurred_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalAmount = (int) $monthlyTransactions->sum('amount');
        $daysInMonth = $selectedMonth->daysInMonth;
        $dailyAverage = $daysInMonth > 0 ? (int) round($totalAmount / $daysInMonth) : 0;

        $categoryBreakdown = $monthlyTransactions
            ->groupBy('category')
            ->map(function ($items, string $category) use ($totalAmount): array {
                $amount = (int) $items->sum('amount');
                $percentage = $totalAmount > 0 ? round(($amount / $totalAmount) * 100, 2) : 0.0;
                $meta = Transaction::metaFor($category);

                return [
                    'category' => $category,
                    'label' => $meta['label'],
                    'full_label' => $meta['full_label'],
                    'subtitle' => $meta['subtitle'],
                    'dominant_desc' => $meta['dominant_desc'],
                    'color' => $meta['color'],
                    'icon' => $meta['icon'],
                    'amount' => $amount,
                    'percentage' => $percentage,
                ];
            })
            ->sortByDesc('amount')
            ->values();

        $dominantCategory = $categoryBreakdown->first();

        // 3-month comparison (M-2, M-1, M)
        $threeMonthStart = $selectedMonth->copy()->subMonths(2)->startOfMonth();
        $threeMonthTransactions = Transaction::query()
            ->forWorkspace($userId)
            ->where('type', $type)
            ->whereBetween('occurred_at', [$threeMonthStart, $endOfMonth])
            ->get();

        $comparison = [];
        for ($i = 2; $i >= 0; $i--) {
            $m = $selectedMonth->copy()->subMonths($i);
            $key = $m->format('Y-m');
            $sum = (int) $threeMonthTransactions
                ->filter(fn (Transaction $t): bool => $t->occurred_at->format('Y-m') === $key)
                ->sum('amount');

            $comparison[] = [
                'label' => Format::monthYear($m),
                'short' => Format::shortMonth($m),
                'amount' => $sum,
                'is_active' => $i === 0,
            ];
        }

        $maxComparison = max(1, ...array_column($comparison, 'amount'));
        foreach ($comparison as &$item) {
            $item['width_percent'] = (int) round(($item['amount'] / $maxComparison) * 100);
        }
        unset($item);

        $prevMonthAmount = $comparison[1]['amount'];
        $currentMonthAmount = $comparison[2]['amount'];
        $deltaPercent = $prevMonthAmount > 0
            ? (int) round((($currentMonthAmount - $prevMonthAmount) / $prevMonthAmount) * 100)
            : 0;
        $prevShortLabel = $comparison[1]['short'];

        return view('charts.index', [
            'selectedMonth' => $selectedMonth,
            'prevMonth' => $selectedMonth->copy()->subMonth(),
            'nextMonth' => $selectedMonth->copy()->addMonth(),
            'type' => $type,
            'totalAmount' => $totalAmount,
            'daysInMonth' => $daysInMonth,
            'dailyAverage' => $dailyAverage,
            'categoryBreakdown' => $categoryBreakdown,
            'dominantCategory' => $dominantCategory,
            'comparison' => $comparison,
            'deltaPercent' => $deltaPercent,
            'prevShortLabel' => $prevShortLabel,
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
