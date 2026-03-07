<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $incomeNames = [
            'Salary',
            'Freelance',
            'Investment',
            'Gift',
            'Other Income',
        ];

        $expenseNames = [
            'Food',
            'Transport',
            'Housing',
            'Utilities',
            'Healthcare',
            'Entertainment',
            'Shopping',
            'Education',
            'Other Expense',
        ];

        foreach (User::all() as $user) {
            foreach ($incomeNames as $name) {
                Category::create([
                    'user_id' => $user->id,
                    'name'    => $name,
                    'type'    => 'income',
                    'color'   => '#10B981',
                ]);
            }

            foreach ($expenseNames as $name) {
                Category::create([
                    'user_id' => $user->id,
                    'name'    => $name,
                    'type'    => 'expense',
                    'color'   => '#EF4444',
                ]);
            }
        }
    }
}