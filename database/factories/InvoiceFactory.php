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
        $amount = $this->faker->randomFloat(2, 100, 10000); // Random amount between 100 and 10,000
        $rcdAmount = $this->faker->optional()->randomFloat(2, 50, $amount); // Optional received amount
        $badDebtAmount = $this->faker->optional()->numberBetween(0, $amount);
        $dueDate = $this->faker->optional()->dateTimeBetween('-1 year', 'now');
        $rcdDueDate = $this->faker->optional()->dateTimeBetween($dueDate, 'now');
//        $badDebtAmount = $status === 'bad_debts' ? $this->faker->randomFloat(2, 0, $amount - $rcdAmount) : null;

        return [
            'clients_id' => Client::factory(),
            'due_date' => $dueDate->format('Y-m-d'),
            'amount' => $amount,
            'rcd_due_date' => $rcdDueDate->format('Y-m-d'),
            'rcd_amount' => $rcdAmount,
            'bad_debt_amount' => $badDebtAmount,
            'invoice_year' => $this->faker->year() . '-' . $this->faker->month(),
            'notes' => $this->faker->optional()->text(200),
            'payment_type' => $this->faker->optional()->randomElement(['bank', 'cash']),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
