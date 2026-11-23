<?php

namespace Database\Seeders;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demoUser = User::query()->updateOrCreate(
            ['email' => 'budi@dompetku.id'],
            [
                'name' => 'Budi Pratama',
                'username' => 'budipratama99',
                'password' => 'password123',
            ],
        );

        $seedForUser = function (?int $userId): void {
            Transaction::query()->forWorkspace($userId)->delete();

            $records = [
                // Juni 2026
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-06-05 08:30:00',
                ],
                [
                    'title' => 'Makanan & Minuman',
                    'description' => 'Belanja Dapur',
                    'type' => TransactionType::Expense,
                    'category' => 'Makanan',
                    'amount' => 180000,
                    'occurred_at' => '2026-06-14 12:15:00',
                ],
                // Juli 2026
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-07-05 08:30:00',
                ],
                [
                    'title' => 'Makanan & Minuman',
                    'description' => 'Konsumsi Harian',
                    'type' => TransactionType::Expense,
                    'category' => 'Makanan',
                    'amount' => 185000,
                    'occurred_at' => '2026-07-12 13:10:00',
                ],
                // Agustus 2026
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-08-05 08:30:00',
                ],
                [
                    'title' => 'Tagihan Internet',
                    'description' => 'Langganan Bulanan',
                    'type' => TransactionType::Expense,
                    'category' => 'Tagihan',
                    'amount' => 180000,
                    'occurred_at' => '2026-08-18 10:00:00',
                ],
                // September 2026 (Total Pengeluaran: Rp1.020.000)
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-09-05 08:30:00',
                ],
                [
                    'title' => 'Tagihan Listrik & Internet',
                    'description' => 'PLN & WiFi Rumah',
                    'type' => TransactionType::Expense,
                    'category' => 'Tagihan',
                    'amount' => 750000,
                    'occurred_at' => '2026-09-10 14:00:00',
                ],
                [
                    'title' => 'Makanan & Minuman',
                    'description' => 'Restoran Keluarga',
                    'type' => TransactionType::Expense,
                    'category' => 'Makanan',
                    'amount' => 230000,
                    'occurred_at' => '2026-09-15 19:20:00',
                ],
                [
                    'title' => 'Pengeluaran Lainnya',
                    'description' => 'Administrasi Bank',
                    'type' => TransactionType::Expense,
                    'category' => 'Lainnya',
                    'amount' => 40000,
                    'occurred_at' => '2026-09-22 11:00:00',
                ],
                // Oktober 2026 (Total Pengeluaran: Rp965.000)
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-10-05 08:30:00',
                ],
                [
                    'title' => 'Tagihan Listrik / Air',
                    'description' => 'PLN & PDAM',
                    'type' => TransactionType::Expense,
                    'category' => 'Tagihan',
                    'amount' => 720000,
                    'occurred_at' => '2026-10-03 14:15:00',
                ],
                [
                    'title' => 'Makanan & Minuman',
                    'description' => 'Restoran Keluarga',
                    'type' => TransactionType::Expense,
                    'category' => 'Makanan',
                    'amount' => 210000,
                    'occurred_at' => '2026-10-03 19:42:00',
                ],
                [
                    'title' => 'Pengeluaran Lainnya',
                    'description' => 'Administrasi Bank',
                    'type' => TransactionType::Expense,
                    'category' => 'Lainnya',
                    'amount' => 35000,
                    'occurred_at' => '2026-10-03 11:10:00',
                ],
                // November 2026 (Total Pemasukan: Rp1.500.000, Total Pengeluaran: Rp850.000)
                [
                    'title' => 'Gajian',
                    'description' => 'Penerimaan Kas',
                    'type' => TransactionType::Income,
                    'category' => 'Gaji',
                    'amount' => 1500000,
                    'occurred_at' => '2026-11-05 08:30:00',
                ],
                [
                    'title' => 'Tagihan Listrik / Air',
                    'description' => 'PLN & PDAM',
                    'type' => TransactionType::Expense,
                    'category' => 'Tagihan',
                    'amount' => 700000,
                    'occurred_at' => '2026-11-03 14:15:00',
                ],
                [
                    'title' => 'Makanan & Minuman',
                    'description' => 'Restoran Keluarga',
                    'type' => TransactionType::Expense,
                    'category' => 'Makanan',
                    'amount' => 130000,
                    'occurred_at' => '2026-11-03 19:42:00',
                ],
                [
                    'title' => 'Pengeluaran Lainnya',
                    'description' => 'Administrasi Bank',
                    'type' => TransactionType::Expense,
                    'category' => 'Lainnya',
                    'amount' => 20000,
                    'occurred_at' => '2026-11-03 11:10:00',
                ],
            ];

            foreach ($records as $record) {
                Transaction::query()->create([
                    ...$record,
                    'user_id' => $userId,
                ]);
            }
        };

        $seedForUser(null);
        $seedForUser($demoUser->id);
    }
}
