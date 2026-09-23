<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinanceDemoSeeder extends Seeder
{
    /**
     * Seeds a week of sample sales, purchases, and expenses so /finance
     * has data to display right after migrating. Run with:
     *   php artisan db:seed --class=FinanceDemoSeeder
     */
    public function run(): void
    {
        $freshFarm = Supplier::create(['name' => 'Fresh Farm Meats', 'contact_no' => '0917-555-0101']);
        $coastal = Supplier::create(['name' => 'Coastal Coffee Traders', 'contact_no' => '0917-555-0102']);
        $greenValley = Supplier::create(['name' => 'Green Valley Produce', 'contact_no' => '0917-555-0103']);
        $bakersRow = Supplier::create(['name' => "Baker's Row Supplies", 'contact_no' => '0917-555-0104']);

        $today = Carbon::now();

        Sale::create(['pos_ref' => 'OR-10231', 'sale_datetime' => $today->copy()->setTime(7, 42), 'order_type' => 'dine', 'payment_method' => 'cash', 'discount' => null, 'total_amount' => 384.00]);
        Sale::create(['pos_ref' => 'OR-10232', 'sale_datetime' => $today->copy()->setTime(8, 15), 'order_type' => 'take', 'payment_method' => 'gcash', 'discount' => null, 'total_amount' => 610.00]);
        Sale::create(['pos_ref' => 'OR-10233', 'sale_datetime' => $today->copy()->setTime(9, 3), 'order_type' => 'dine', 'payment_method' => 'cash', 'discount' => 'Senior', 'total_amount' => 280.00]);
        Sale::create(['pos_ref' => 'OR-10228', 'sale_datetime' => $today->copy()->subDay()->setTime(18, 58), 'order_type' => 'dine', 'payment_method' => 'cash', 'discount' => null, 'total_amount' => 515.00]);
        Sale::create(['pos_ref' => 'OR-10229', 'sale_datetime' => $today->copy()->subDay()->setTime(19, 20), 'order_type' => 'take', 'payment_method' => 'gcash', 'discount' => null, 'total_amount' => 190.00]);
        Sale::create(['pos_ref' => 'OR-10230', 'sale_datetime' => $today->copy()->subDay()->setTime(21, 47), 'order_type' => 'dine', 'payment_method' => 'cash', 'discount' => 'PWD', 'total_amount' => 640.00]);
        Sale::create(['pos_ref' => 'OR-10221', 'sale_datetime' => $today->copy()->subDays(2)->setTime(12, 11), 'order_type' => 'take', 'payment_method' => 'cash', 'discount' => null, 'total_amount' => 350.00]);
        Sale::create(['pos_ref' => 'OR-10222', 'sale_datetime' => $today->copy()->subDays(2)->setTime(13, 34), 'order_type' => 'dine', 'payment_method' => 'gcash', 'discount' => null, 'total_amount' => 420.00]);

        $po1 = Purchase::create(['purchase_no' => 'PO-0512', 'supplier_id' => $freshFarm->id, 'purchase_date' => $today->copy()->subDay(), 'items_count' => 4, 'status' => 'received', 'total_cost' => 12400.00]);
        $po2 = Purchase::create(['purchase_no' => 'PO-0513', 'supplier_id' => $coastal->id, 'purchase_date' => $today->copy()->subDay(), 'items_count' => 2, 'status' => 'received', 'total_cost' => 6200.00]);
        Purchase::create(['purchase_no' => 'PO-0514', 'supplier_id' => $greenValley->id, 'purchase_date' => $today->copy()->subDays(2), 'items_count' => 6, 'status' => 'pending', 'total_cost' => 4380.00]);
        $po4 = Purchase::create(['purchase_no' => 'PO-0515', 'supplier_id' => $bakersRow->id, 'purchase_date' => $today->copy()->subDays(3), 'items_count' => 3, 'status' => 'received', 'total_cost' => 3150.00]);
        $po5 = Purchase::create(['purchase_no' => 'PO-0516', 'supplier_id' => $freshFarm->id, 'purchase_date' => $today->copy()->subDays(4), 'items_count' => 5, 'status' => 'received', 'total_cost' => 9870.00]);
        Purchase::create(['purchase_no' => 'PO-0517', 'supplier_id' => $greenValley->id, 'purchase_date' => $today->copy()->subDays(5), 'items_count' => 2, 'status' => 'pending', 'total_cost' => 1620.00]);

        Expense::create(['expense_no' => 'EX-0301', 'category' => 'Ingredients', 'expense_date' => $today->copy()->subDay(), 'recorded_by' => 'J. Cruz', 'purchase_id' => $po1->id, 'amount' => 12400.00]);
        Expense::create(['expense_no' => 'EX-0302', 'category' => 'Ingredients', 'expense_date' => $today->copy()->subDay(), 'recorded_by' => 'J. Cruz', 'purchase_id' => $po2->id, 'amount' => 6200.00]);
        Expense::create(['expense_no' => 'EX-0303', 'category' => 'Utilities', 'expense_date' => $today->copy()->subDays(2), 'recorded_by' => 'M. Reyes', 'purchase_id' => null, 'amount' => 8450.00]);
        Expense::create(['expense_no' => 'EX-0304', 'category' => 'Repairs', 'expense_date' => $today->copy()->subDays(3), 'recorded_by' => 'M. Reyes', 'purchase_id' => null, 'amount' => 2100.00]);
        Expense::create(['expense_no' => 'EX-0305', 'category' => 'Ingredients', 'expense_date' => $today->copy()->subDays(3), 'recorded_by' => 'J. Cruz', 'purchase_id' => $po4->id, 'amount' => 3150.00]);
        Expense::create(['expense_no' => 'EX-0306', 'category' => 'Supplies', 'expense_date' => $today->copy()->subDays(4), 'recorded_by' => 'M. Reyes', 'purchase_id' => null, 'amount' => 1380.00]);
        Expense::create(['expense_no' => 'EX-0307', 'category' => 'Ingredients', 'expense_date' => $today->copy()->subDays(4), 'recorded_by' => 'J. Cruz', 'purchase_id' => $po5->id, 'amount' => 9870.00]);
    }
}
