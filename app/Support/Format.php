<?php

namespace App\Support;

use Carbon\CarbonInterface;

class Format
{
    /**
     * @var array<int, string>
     */
    private const MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * @var array<int, string>
     */
    private const SHORT_MONTHS = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agt',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];

    /**
     * @var array<int, string>
     */
    private const DAYS = [
        0 => 'MINGGU',
        1 => 'SENIN',
        2 => 'SELASA',
        3 => 'RABU',
        4 => 'KAMIS',
        5 => 'JUMAT',
        6 => 'SABTU',
    ];

    public static function rupiah(int|float $amount, bool $space = false): string
    {
        $prefix = $space ? 'Rp ' : 'Rp';
        $abs = abs((int) round($amount));
        $formatted = number_format($abs, 0, ',', '.');

        return ($amount < 0 ? '-' : '').$prefix.$formatted;
    }

    public static function rupiahShort(int|float $amount): string
    {
        $abs = abs((int) round($amount));

        if ($abs === 0) {
            return 'Rp 0';
        }

        if ($abs >= 1_000_000) {
            $millions = $abs / 1_000_000;
            $formatted = fmod($millions, 1.0) === 0.0
                ? number_format($millions, 0, ',', '.')
                : number_format($millions, 1, ',', '.');

            return 'Rp '.$formatted.'jt';
        }

        if ($abs >= 1_000) {
            return 'Rp '.number_format((int) round($abs / 1_000), 0, ',', '.').'rb';
        }

        return 'Rp '.number_format($abs, 0, ',', '.');
    }

    public static function percent(float $value): string
    {
        return number_format($value, 2, ',', '.').'%';
    }

    public static function monthYear(CarbonInterface $date): string
    {
        return self::MONTHS[$date->month].' '.$date->year;
    }

    public static function shortMonth(CarbonInterface $date): string
    {
        return self::SHORT_MONTHS[$date->month];
    }

    public static function shortMonthYear(CarbonInterface $date): string
    {
        return self::SHORT_MONTHS[$date->month].' '.$date->year;
    }

    public static function fullDate(CarbonInterface $date): string
    {
        return $date->format('d').' '.self::MONTHS[$date->month].' '.$date->year;
    }

    public static function dayName(CarbonInterface $date): string
    {
        return self::DAYS[$date->dayOfWeek];
    }
}
