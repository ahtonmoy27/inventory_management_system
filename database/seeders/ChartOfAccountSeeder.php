<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1001', 'name' => 'Cash', 'type' => 'asset', 'category' => 'Current Assets', 'is_system' => true],
            ['code' => '1002', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'Current Assets', 'is_system' => true],
            ['code' => '1003', 'name' => 'Inventory', 'type' => 'asset', 'category' => 'Current Assets', 'is_system' => true],
            ['code' => '2001', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'Current Liabilities', 'is_system' => true],
            ['code' => '2002', 'name' => 'VAT Payable', 'type' => 'liability', 'category' => 'Current Liabilities', 'is_system' => true],
            ['code' => '3001', 'name' => 'Owner Equity', 'type' => 'equity', 'category' => 'Owner Equity', 'is_system' => true],
            ['code' => '4001', 'name' => 'Sales Revenue', 'type' => 'revenue', 'category' => 'Operating Revenue', 'is_system' => true],
            ['code' => '4002', 'name' => 'Sales Discount', 'type' => 'revenue', 'category' => 'Contra Revenue', 'is_system' => true],
            ['code' => '5001', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'category' => 'Cost of Sales', 'is_system' => true],
            ['code' => '5002', 'name' => 'Office Expense', 'type' => 'expense', 'category' => 'Operating Expense', 'is_system' => false],
            ['code' => '5003', 'name' => 'Rent Expense', 'type' => 'expense', 'category' => 'Operating Expense', 'is_system' => false],
            ['code' => '5004', 'name' => 'Utilities Expense', 'type' => 'expense', 'category' => 'Operating Expense', 'is_system' => false],
            ['code' => '5005', 'name' => 'Salary Expense', 'type' => 'expense', 'category' => 'Operating Expense', 'is_system' => false],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
