<?php

namespace Tests\Feature;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DompetkuTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_and_auth_pages_render(): void
    {
        $this->get(route('home'))->assertRedirect(route('login'));
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('charts.index'))->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke Akun Anda');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Buat Akun Baru');
    }

    public function test_authenticated_user_can_view_all_dashboard_pages(): void
    {
        $this->seed();
        $user = User::query()->where('username', 'budipratama99')->firstOrFail();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Ringkasan Dompet')
            ->assertSee('Daftar Transaksi')
            ->assertSee('Tambah Transaksi Cepat');

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Laporan Tren Pengeluaran')
            ->assertSee('Ringkasan 6 Bulan');

        $this->actingAs($user)
            ->get(route('charts.index'))
            ->assertOk()
            ->assertSee('Statistik Pengeluaran')
            ->assertSee('Komposisi Pengeluaran')
            ->assertSee('Rincian per Kategori')
            ->assertSee('Perbandingan 3 Bulan');
    }

    public function test_user_can_register_and_login_with_username_or_email(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Budi Pratama',
            'username' => 'budipratama99',
            'email' => 'budi@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'budipratama99',
            'email' => 'budi@example.com',
        ]);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login.attempt'), [
            'login' => 'budipratama99',
            'password' => 'secret1234',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    public function test_user_can_create_and_delete_transactions(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'type' => TransactionType::Expense->value,
                'title' => 'Tagihan Listrik / Air',
                'amount' => 700000,
                'occurred_at' => '2026-11-03',
                'category' => 'Tagihan',
            ])
            ->assertRedirect(route('home', ['month' => '2026-11']));

        $transaction = Transaction::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($transaction);
        $this->assertSame(700000, $transaction->amount);

        $this->actingAs($user)
            ->delete(route('transactions.destroy', $transaction))
            ->assertRedirect(route('home', ['month' => '2026-11']));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
