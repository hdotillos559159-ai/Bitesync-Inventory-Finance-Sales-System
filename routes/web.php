<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.authenticate');

});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');


    /*
    |--------------------------------------------------------------------------
    | CEO / ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:CEO/Admin')->group(function () {

        Route::get('/admin/dashboard', [
            DashboardController::class,
            'admin'
        ])->name('admin.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | FINANCE DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Finance')->group(function () {

        Route::get('/finance/dashboard', [
            DashboardController::class,
            'finance'
        ])->name('finance.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | PROCUREMENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Procurement')->group(function () {

        Route::get('/procurement/dashboard', [
            DashboardController::class,
            'procurement'
        ])->name('procurement.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | INVENTORY - VIEW
    |
    | CEO/Admin  = Full access
    | Finance     = View only
    | Procurement = Manage
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Finance,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Inventory List
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory', [
            InventoryController::class,
            'index'
        ])->name('inventory.index');

    });


    /*
    |--------------------------------------------------------------------------
    | INVENTORY - MANAGEMENT
    |
    | CEO/Admin  = Full access
    | Procurement = Manage
    |
    | Finance is intentionally excluded.
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Add Inventory Item
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory/create', [
            InventoryController::class,
            'create'
        ])->name('inventory.create');


        /*
        |--------------------------------------------------------------------------
        | Store Inventory Item
        |--------------------------------------------------------------------------
        */

        Route::post('/inventory', [
            InventoryController::class,
            'store'
        ])->name('inventory.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Inventory Item
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory/{inventoryItem}/edit', [
            InventoryController::class,
            'edit'
        ])->name('inventory.edit');


        /*
        |--------------------------------------------------------------------------
        | Update Inventory Item
        |--------------------------------------------------------------------------
        */

        Route::put('/inventory/{inventoryItem}', [
            InventoryController::class,
            'update'
        ])->name('inventory.update');


        /*
        |--------------------------------------------------------------------------
        | Stock Operations Page
        |
        | This page is where the user can:
        | - Add Stock
        | - Remove Stock
        | - Adjust Stock
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory/{inventoryItem}/stock', [
            InventoryController::class,
            'stockForm'
        ])->name('inventory.stock');


        /*
        |--------------------------------------------------------------------------
        | Stock In
        |--------------------------------------------------------------------------
        */

        Route::post('/inventory/{inventoryItem}/stock-in', [
            InventoryController::class,
            'stockIn'
        ])->name('inventory.stock-in');


        /*
        |--------------------------------------------------------------------------
        | Stock Out
        |--------------------------------------------------------------------------
        */

        Route::post('/inventory/{inventoryItem}/stock-out', [
            InventoryController::class,
            'stockOut'
        ])->name('inventory.stock-out');


        /*
        |--------------------------------------------------------------------------
        | Adjust Stock
        |--------------------------------------------------------------------------
        */

        Route::post('/inventory/{inventoryItem}/adjust', [
            InventoryController::class,
            'adjust'
        ])->name('inventory.adjust');

    });


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS - VIEW
    |
    | CEO/Admin  = Full access
    | Finance     = View only
    | Procurement = View only
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Finance,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Product List
        |--------------------------------------------------------------------------
        */

        Route::get('/products', [
            ProductController::class,
            'index'
        ])->name('products.index');

    });


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS - MANAGEMENT
    |
    | CEO/Admin = Add and Edit
    |
    | Finance and Procurement are intentionally excluded.
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Add Product Page
        |--------------------------------------------------------------------------
        */

        Route::get('/products/create', [
            ProductController::class,
            'create'
        ])->name('products.create');


        /*
        |--------------------------------------------------------------------------
        | Store Product
        |--------------------------------------------------------------------------
        */

        Route::post('/products', [
            ProductController::class,
            'store'
        ])->name('products.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Product Page
        |--------------------------------------------------------------------------
        */

        Route::get('/products/{product}/edit', [
            ProductController::class,
            'edit'
        ])->name('products.edit');


        /*
        |--------------------------------------------------------------------------
        | Update Product
        |--------------------------------------------------------------------------
        */

        Route::put('/products/{product}', [
            ProductController::class,
            'update'
        ])->name('products.update');

    });


    /*
    |--------------------------------------------------------------------------
    | RECIPES
    |
    | CEO/Admin only
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Recipe Page
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/products/{product}/recipe',
            [
                RecipeController::class,
                'edit'
            ]
        )->name('recipes.edit');


        /*
        |--------------------------------------------------------------------------
        | Add Recipe Ingredient
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/products/{product}/recipe/items',
            [
                RecipeController::class,
                'addItem'
            ]
        )->name('recipes.items.store');


        /*
        |--------------------------------------------------------------------------
        | Update Recipe Ingredient
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/products/{product}/recipe/items/{recipeItem}',
            [
                RecipeController::class,
                'updateItem'
            ]
        )->name('recipes.items.update');


        /*
        |--------------------------------------------------------------------------
        | Remove Recipe Ingredient
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/products/{product}/recipe/items/{recipeItem}',
            [
                RecipeController::class,
                'removeItem'
            ]
        )->name('recipes.items.destroy');


        /*
        |--------------------------------------------------------------------------
        | Update Recipe Instructions
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/products/{product}/recipe/instructions',
            [
                RecipeController::class,
                'updateInstructions'
            ]
        )->name('recipes.instructions.update');

    });


    /*
    |--------------------------------------------------------------------------
    | SUPPLIERS - MANAGEMENT
    |
    | CEO/Admin  = Full access
    | Procurement = Manage
    |
    | Finance is intentionally excluded.
    |
    | IMPORTANT:
    | Static routes such as /suppliers/create are placed BEFORE
    | /suppliers/{supplier} so "create" is not interpreted as
    | a supplier ID.
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Add Supplier Page
        |--------------------------------------------------------------------------
        */

        Route::get('/suppliers/create', [
            SupplierController::class,
            'create'
        ])->name('suppliers.create');


        /*
        |--------------------------------------------------------------------------
        | Store Supplier
        |--------------------------------------------------------------------------
        */

        Route::post('/suppliers', [
            SupplierController::class,
            'store'
        ])->name('suppliers.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Supplier Page
        |--------------------------------------------------------------------------
        */

        Route::get('/suppliers/{supplier}/edit', [
            SupplierController::class,
            'edit'
        ])->name('suppliers.edit');


        /*
        |--------------------------------------------------------------------------
        | Update Supplier
        |--------------------------------------------------------------------------
        */

        Route::put('/suppliers/{supplier}', [
            SupplierController::class,
            'update'
        ])->name('suppliers.update');


        /*
        |--------------------------------------------------------------------------
        | Deactivate Supplier
        |--------------------------------------------------------------------------
        */

        Route::delete('/suppliers/{supplier}', [
            SupplierController::class,
            'destroy'
        ])->name('suppliers.destroy');


        /*
        |--------------------------------------------------------------------------
        | Reactivate Supplier
        |--------------------------------------------------------------------------
        */

        Route::patch('/suppliers/{supplier}/activate', [
            SupplierController::class,
            'activate'
        ])->name('suppliers.activate');

    });


    /*
    |--------------------------------------------------------------------------
    | SUPPLIERS - VIEW
    |
    | CEO/Admin  = View
    | Finance     = View only
    | Procurement = View
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Finance,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Supplier List
        |--------------------------------------------------------------------------
        */

        Route::get('/suppliers', [
            SupplierController::class,
            'index'
        ])->name('suppliers.index');


        /*
        |--------------------------------------------------------------------------
        | Supplier Details
        |
        | This must remain AFTER /suppliers/create.
        |--------------------------------------------------------------------------
        */

        Route::get('/suppliers/{supplier}', [
            SupplierController::class,
            'show'
        ])->name('suppliers.show');

    });


    /*
    |--------------------------------------------------------------------------
    | PURCHASES - MANAGEMENT
    |
    | CEO/Admin  = Create, edit, approve, reject, order, cancel
    | Procurement = Create, edit, submit, order, cancel
    |
    | Finance is intentionally excluded from management actions.
    |
    | IMPORTANT:
    | Static routes such as /purchases/create are placed BEFORE
    | /purchases/{purchase}.
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Create Purchase Page
        |--------------------------------------------------------------------------
        */

        Route::get('/purchases/create', [
            PurchaseController::class,
            'create'
        ])->name('purchases.create');


        /*
        |--------------------------------------------------------------------------
        | Store Purchase
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases', [
            PurchaseController::class,
            'store'
        ])->name('purchases.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Purchase Page
        |--------------------------------------------------------------------------
        */

        Route::get('/purchases/{purchase}/edit', [
            PurchaseController::class,
            'edit'
        ])->name('purchases.edit');


        /*
        |--------------------------------------------------------------------------
        | Update Purchase
        |--------------------------------------------------------------------------
        */

        Route::put('/purchases/{purchase}', [
            PurchaseController::class,
            'update'
        ])->name('purchases.update');


        /*
        |--------------------------------------------------------------------------
        | Submit Purchase for Approval
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases/{purchase}/submit', [
            PurchaseController::class,
            'submit'
        ])->name('purchases.submit');


        /*
        |--------------------------------------------------------------------------
        | Approve Purchase
        |
        | Controller additionally checks that the user is CEO/Admin.
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases/{purchase}/approve', [
            PurchaseController::class,
            'approve'
        ])->name('purchases.approve');


        /*
        |--------------------------------------------------------------------------
        | Reject Purchase
        |
        | Controller additionally checks that the user is CEO/Admin.
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases/{purchase}/reject', [
            PurchaseController::class,
            'reject'
        ])->name('purchases.reject');


        /*
        |--------------------------------------------------------------------------
        | Mark Purchase as Ordered
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases/{purchase}/order', [
            PurchaseController::class,
            'order'
        ])->name('purchases.order');


        /*
        |--------------------------------------------------------------------------
        | Cancel Purchase
        |--------------------------------------------------------------------------
        */

        Route::post('/purchases/{purchase}/cancel', [
            PurchaseController::class,
            'cancel'
        ])->name('purchases.cancel');

    });


    /*
    |--------------------------------------------------------------------------
    | PURCHASES - VIEW
    |
    | CEO/Admin  = View
    | Finance     = View only
    | Procurement = View
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Finance,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Purchase List
        |--------------------------------------------------------------------------
        */

        Route::get('/purchases', [
            PurchaseController::class,
            'index'
        ])->name('purchases.index');


        /*
        |--------------------------------------------------------------------------
        | Purchase Details
        |
        | This must remain AFTER /purchases/create and
        | the other static purchase routes.
        |--------------------------------------------------------------------------
        */

        Route::get('/purchases/{purchase}', [
            PurchaseController::class,
            'show'
        ])->name('purchases.show');

    });


    /*
    |--------------------------------------------------------------------------
    | SALES - MANAGEMENT
    |
    | CEO/Admin = Create, complete, and cancel sales
    |
    | Finance and Procurement are intentionally excluded
    | from management actions.
    |
    | IMPORTANT:
    | Static route /sales/create is placed BEFORE
    | /sales/{sale} so "create" is not interpreted as
    | a sale ID.
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Create Sale Page
        |--------------------------------------------------------------------------
        */

        Route::get('/sales/create', [
            SalesController::class,
            'create'
        ])->name('sales.create');


        /*
        |--------------------------------------------------------------------------
        | Store Sale
        |--------------------------------------------------------------------------
        */

        Route::post('/sales', [
            SalesController::class,
            'store'
        ])->name('sales.store');


        /*
        |--------------------------------------------------------------------------
        | Cancel Sale
        |--------------------------------------------------------------------------
        */

        Route::post('/sales/{sale}/cancel', [
            SalesController::class,
            'cancel'
        ])->name('sales.cancel');

    });


    /*
    |--------------------------------------------------------------------------
    | SALES - VIEW
    |
    | CEO/Admin  = View
    | Finance     = View
    | Procurement = View
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:CEO/Admin,Finance,Procurement'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Sales List
        |--------------------------------------------------------------------------
        */

        Route::get('/sales', [
            SalesController::class,
            'index'
        ])->name('sales.index');


        /*
        |--------------------------------------------------------------------------
        | Sales Details
        |
        | This must remain AFTER /sales/create and
        | the other static sales routes.
        |--------------------------------------------------------------------------
        */

        Route::get('/sales/{sale}', [
            SalesController::class,
            'show'
        ])->name('sales.show');

    });

});