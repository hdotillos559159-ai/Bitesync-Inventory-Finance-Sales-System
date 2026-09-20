<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Display the recipe for a product.
     */
    public function edit(
        Request $request,
        Product $product
    ): View {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'You are not authorized to manage recipes.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get or create the recipe
        |--------------------------------------------------------------------------
        |
        | Each product can have one recipe.
        |
        */

        $recipe = Recipe::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'instructions' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Load recipe ingredients
        |--------------------------------------------------------------------------
        */

        $recipe->load([
            'items.inventoryItem.unit',
            'items.inventoryItem.category',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Inventory items available as ingredients
        |--------------------------------------------------------------------------
        |
        | Only active inventory items are shown.
        |
        */

        $inventoryItems = InventoryItem::with([
            'unit',
            'category',
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('recipes.edit', [
            'user' => $user,
            'product' => $product,
            'recipe' => $recipe,
            'inventoryItems' => $inventoryItems,
        ]);
    }

    /**
     * Add an inventory ingredient to a recipe.
     */
    public function addItem(
        Request $request,
        Product $product
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'You are not authorized to manage recipes.'
            );
        }

        $validated = $request->validate([
            'inventory_item_id' => [
                'required',
                'exists:inventory_items,id',
            ],

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get or create the product recipe
        |--------------------------------------------------------------------------
        */

        $recipe = Recipe::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'instructions' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate ingredients
        |--------------------------------------------------------------------------
        */

        $alreadyExists = RecipeItem::where(
            'recipe_id',
            $recipe->id
        )
            ->where(
                'inventory_item_id',
                $validated['inventory_item_id']
            )
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors([
                    'inventory_item_id' =>
                        'This inventory item is already included in the recipe.',
                ])
                ->withInput();
        }

        RecipeItem::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' =>
                $validated['inventory_item_id'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()
            ->route(
                'recipes.edit',
                $product
            )
            ->with(
                'success',
                'Ingredient added to the recipe successfully.'
            );
    }

    /**
     * Update the quantity of an existing recipe ingredient.
     */
    public function updateItem(
        Request $request,
        Product $product,
        RecipeItem $recipeItem
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'You are not authorized to manage recipes.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure the recipe item belongs to this product
        |--------------------------------------------------------------------------
        */

        $recipe = Recipe::where(
            'product_id',
            $product->id
        )->firstOrFail();

        if ($recipeItem->recipe_id !== $recipe->id) {
            abort(
                404,
                'Recipe ingredient not found.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        $recipeItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return redirect()
            ->route(
                'recipes.edit',
                $product
            )
            ->with(
                'success',
                'Ingredient quantity updated successfully.'
            );
    }

    /**
     * Remove an ingredient from a recipe.
     */
    public function removeItem(
        Request $request,
        Product $product,
        RecipeItem $recipeItem
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'You are not authorized to manage recipes.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure the recipe item belongs to this product
        |--------------------------------------------------------------------------
        */

        $recipe = Recipe::where(
            'product_id',
            $product->id
        )->firstOrFail();

        if ($recipeItem->recipe_id !== $recipe->id) {
            abort(
                404,
                'Recipe ingredient not found.'
            );
        }

        $recipeItem->delete();

        return redirect()
            ->route(
                'recipes.edit',
                $product
            )
            ->with(
                'success',
                'Ingredient removed from the recipe.'
            );
    }

    /**
     * Save recipe instructions.
     */
    public function updateInstructions(
        Request $request,
        Product $product
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'You are not authorized to manage recipes.'
            );
        }

        $validated = $request->validate([
            'instructions' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $recipe = Recipe::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'instructions' => null,
            ]
        );

        $recipe->update([
            'instructions' =>
                $validated['instructions'] ?? null,
        ]);

        return redirect()
            ->route(
                'recipes.edit',
                $product
            )
            ->with(
                'success',
                'Recipe instructions saved successfully.'
            );
    }
}