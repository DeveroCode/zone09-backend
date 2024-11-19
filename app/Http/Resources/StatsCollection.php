<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StatsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $today = Carbon::now();
        $yesterday = $today->copy()->subDay();
        // Start of month
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // Next month
        $startOfpreviousMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfpreviousMonth = $today->copy()->subMonth()->endOfMonth();

        // Calculate the percentage
        // Percentage amounth
        $currentMonthEarnings = $this->monthlyIncomes($startOfMonth, $endOfMonth);
        $totalEarningsTrue = $this->totalEarningsMonth($startOfMonth, $endOfMonth);
        $previousMonthEarnings = $this->monthlyIncomes($startOfpreviousMonth, $endOfpreviousMonth);

        // $realPercentageMonth = $this->highOrLow($totalEarningsTrue, $previousMonthEarnings);
        $currentMonthPercentage = $this->highOrLow($currentMonthEarnings, $previousMonthEarnings);

        // Current daily
        $currentTodayEarnings = $this->dailyIncome($today);
        $previousTodayEarnings = $this->dailyIncome($yesterday);
        $currentTodayPercentage = $this->highOrLow($currentTodayEarnings, $previousTodayEarnings);

        // Current total 
        $realProfitEarnings = $this->totalEarnings($today);
        $previousProfitEarnings = $this->yesterdayEarnings($yesterday);

        $realProfitPercentage = $this->highOrLow($realProfitEarnings, $previousProfitEarnings);

        // Rent month
        $monthlyRents = $this->monthlyRents($startOfMonth, $endOfMonth);
        $previousRents = $this->monthlyIncomes($startOfpreviousMonth, $endOfpreviousMonth);

        $monthlyRentsPercentage = $this->highOrLow($monthlyRents, $previousRents);

        $stats = [
            'ganacias_mensuales' => [
                'value' => $currentMonthEarnings,
                'percentage' => $currentMonthPercentage,
            ],
            'ganancias_diarias' => [
                'value' => $currentTodayEarnings,
                'percentage' => $currentTodayPercentage,
            ],
            'rentas_mensuales' => [
                'value' => $monthlyRents,
                'percentage' => $monthlyRentsPercentage,
            ],
            'ganancias_totales' => [
                'value' => $realProfitEarnings,
                'percentage' => $realProfitPercentage, 
            ]
        ];
        

        return collect($stats)->map(function ($value, $key){
            return new StatsResource(['key' => $key, 'value' => $value]);
        })->values()->toArray();
    }

    public function highOrLow($currentValue, $previousValue){
        if ($currentValue === 0 && $previousValue === 0) {
            return 0;
        }else if($previousValue === 0) {
            return $currentValue > 0 ? 100 : 0;
        }
        $percentageChange = (($currentValue - $previousValue) / $previousValue) * 100;

        return round($percentageChange, 1);
        
    }

    private function monthlyIncomes($startOfMonth, $endOfMonth)
    {
        return Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
    }

    private function dailyIncome($today)
    {
        return Reservation::whereDate('created_at', $today)->sum('total_amount');
    }

    private function monthlyRents($startOfMonth, $endOfMonth)
    {
        return Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    }

    private function totalEarnings($today)
    {
        return Reservation::whereDate('created_at', $today)->where('loan', true)->sum('total_amount');
    }

    private function yesterdayEarnings($day = null)
    {
        return Reservation::whereDate('created_at', $day)->where('loan', true)->sum('total_amount');
    }

    private function totalEarningsMonth($startOfMonth, $endOfMonth)
    {
        return Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('loan', true)->sum('total_amount');
    }
}