<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Invoice::class;
    public function definition(): array
    {
        $status = $this->faker->optional()->randomElement(['bad_debts', 'good']);
        $amount = $this->faker->randomFloat(2, 100, 10000);
        $rcdAmount = $this->faker->optional()->randomFloat(2, 50, $amount);
        $badDebtAmount = $this->faker->optional()->numberBetween(0, $amount);
        $dueDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $rcdDueDate = ( $rcdAmount != null || $badDebtAmount != null ) ? $this->faker->dateTimeBetween($dueDate, 'now') : null;

        // Logic for invoice_year like "25-26"
        $month = (int) $dueDate->format('m');
        $year = (int) $dueDate->format('Y');

        if ($month >= 4) {
            $startYear = $year % 100;
            $endYear = ($year + 1) % 100;
        } else {
            $startYear = ($year - 1) % 100;
            $endYear = $year % 100;
        }

        $invoiceYear = sprintf('%02d-%02d', $startYear, $endYear);

        return [
            'clients_id' => Client::inRandomOrder()->first()?->id ?? Client::factory(),
            'due_date' => $dueDate->format('Y-m-d'),
            'amount' => $amount,
            'rcd_due_date' => $rcdDueDate?->format('Y-m-d'),
            'rcd_amount' => $rcdAmount,
            'bad_debt_amount' => $badDebtAmount,
            'invoice_year' => $invoiceYear, // formatted like "25-26"
            'notes' => $this->faker->optional()->text(200),
            'payment_type' => $this->faker->optional()->randomElement(['bank', 'cash']),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
