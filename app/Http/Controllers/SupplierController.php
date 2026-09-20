<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display the supplier list.
     *
     * Accessible by:
     * - CEO/Admin
     * - Finance
     * - Procurement
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $search = $request->input('search');
        $status = $request->input('status');

        $query = Supplier::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere(
                        'contact_person',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status === 'active') {
            $query->where(
                'status',
                'Active'
            );
        }

        if ($status === 'inactive') {
            $query->where(
                'status',
                'Inactive'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Supplier List
        |--------------------------------------------------------------------------
        */

        $suppliers = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary Statistics
        |--------------------------------------------------------------------------
        */

        $totalSuppliers = Supplier::count();

        $activeSuppliers = Supplier::where(
            'status',
            'Active'
        )->count();

        $inactiveSuppliers = Supplier::where(
            'status',
            'Inactive'
        )->count();

        return view('suppliers.index', [
            'user' => $user,
            'suppliers' => $suppliers,
            'totalSuppliers' => $totalSuppliers,
            'activeSuppliers' => $activeSuppliers,
            'inactiveSuppliers' => $inactiveSuppliers,
            'search' => $search,
            'status' => $status,
        ]);
    }


    /**
     * Show the add supplier form.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add suppliers.'
            );
        }

        return view('suppliers.create', [
            'user' => $user,
        ]);
    }


    /**
     * Store a new supplier.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add suppliers.'
            );
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Inactive',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        Supplier::create([
            'name' =>
                $validated['name'],

            'contact_person' =>
                $validated['contact_person'] ?? null,

            'phone' =>
                $validated['phone'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'address' =>
                $validated['address'] ?? null,

            'status' =>
                $validated['status'],

            'notes' =>
                $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier added successfully.'
            );
    }


    /**
     * Display supplier details.
     *
     * Accessible by:
     * - CEO/Admin
     * - Finance
     * - Procurement
     */
    public function show(
        Request $request,
        Supplier $supplier
    ): View {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Do NOT load purchases yet.
        |--------------------------------------------------------------------------
        |
        | The Purchases module has not been implemented yet.
        | Once the Purchase model and relationship exist, we can add:
        |
        | $supplier->load('purchases');
        |
        */

        return view('suppliers.show', [
            'user' => $user,
            'supplier' => $supplier,
        ]);
    }


    /**
     * Show the edit supplier form.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function edit(
        Request $request,
        Supplier $supplier
    ): View {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit suppliers.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | This is what provides $supplier to:
        |
        | resources/views/suppliers/edit.blade.php
        |--------------------------------------------------------------------------
        */

        return view('suppliers.edit', [
            'user' => $user,
            'supplier' => $supplier,
        ]);
    }


    /**
     * Update an existing supplier.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function update(
        Request $request,
        Supplier $supplier
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit suppliers.'
            );
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Inactive',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $supplier->update([
            'name' =>
                $validated['name'],

            'contact_person' =>
                $validated['contact_person'] ?? null,

            'phone' =>
                $validated['phone'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'address' =>
                $validated['address'] ?? null,

            'status' =>
                $validated['status'],

            'notes' =>
                $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'suppliers.show',
                $supplier
            )
            ->with(
                'success',
                'Supplier updated successfully.'
            );
    }


    /**
     * Deactivate a supplier.
     *
     * We do not permanently delete the supplier because
     * future purchase records may reference it.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function destroy(
        Request $request,
        Supplier $supplier
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to deactivate suppliers.'
            );
        }

        if ($supplier->status === 'Inactive') {
            return redirect()
                ->route('suppliers.index')
                ->with(
                    'success',
                    'Supplier is already inactive.'
                );
        }

        $supplier->update([
            'status' => 'Inactive',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier deactivated successfully.'
            );
    }


    /**
     * Reactivate an inactive supplier.
     *
     * Accessible by:
     * - CEO/Admin
     * - Procurement
     */
    public function activate(
        Request $request,
        Supplier $supplier
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to activate suppliers.'
            );
        }

        if ($supplier->status === 'Active') {
            return redirect()
                ->route('suppliers.index')
                ->with(
                    'success',
                    'Supplier is already active.'
                );
        }

        $supplier->update([
            'status' => 'Active',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier activated successfully.'
            );
    }
}