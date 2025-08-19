<?php

namespace App\Http\Controllers;

use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StoneType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\StockPosition;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(): View
    {
        $polishTypes = PolishType::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $stoneTypes = StoneType::orderBy('name')->get();
        $users = User::orderBy('email')->get();
        
        return view('settings', compact('polishTypes', 'productTypes', 'stoneTypes', 'users'));
    }
    
    public function addPolishType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polish_types',
        ]);
        
        $validated['is_active'] = true;
        
        PolishType::create($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно добавлен.');
    }
    
    public function updatePolishType(Request $request, PolishType $polishType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polish_types,name,' . $polishType->id,
            'is_active' => 'required|boolean',
        ]);
        
        $polishType->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно обновлен.');
    }
    
    public function deletePolishType(PolishType $polishType): RedirectResponse
    {
        $usageCount = StockPosition::where('polish_type_id', $polishType->id)->count();
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид полировки, так как он используется в {$usageCount} позициях.");
        }
        
        $polishType->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно удален.');
    }
    
    public function addProductType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_types',
        ]);
        
        $validated['is_active'] = true;
        
        ProductType::create($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно добавлен.');
    }
    
    public function updateProductType(Request $request, ProductType $productType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name,' . $productType->id,
            'is_active' => 'required|boolean',
        ]);
        
        $productType->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно обновлен.');
    }
    
    public function deleteProductType(ProductType $productType): RedirectResponse
    {
        $usageCount = StockPosition::where('product_type_id', $productType->id)->count();
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид продукции, так как он используется в {$usageCount} позициях.");
        }
        
        $productType->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно удален.');
    }

    public function addStoneType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stone_types',
        ]);
        
        $validated['is_active'] = true;
        
        StoneType::create($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид камня успешно добавлен.');
    }
    
    public function updateStoneType(Request $request, StoneType $stoneType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stone_types,name,' . $stoneType->id,
            'is_active' => 'required|boolean',
        ]);
        
        $stoneType->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид камня успешно обновлен.');
    }
    
    public function deleteStoneType(StoneType $stoneType): RedirectResponse
    {
        $usageCount = StockPosition::where('stone_type_id', $stoneType->id)->count();
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид камня, так как он используется в {$usageCount} позициях.");
        }
        
        $stoneType->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид камня успешно удален.');
    }

    /**
     * Create a new user with generated password and selected role.
     */
    public function createUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'alpha_dash', 'min:3', 'max:30', 'unique:users,username', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email', 'required_without:username'],
            'role' => ['nullable', 'in:viewer'],
        ]);

        $passwordPlain = str()->password(12);

        // Имя может быть необязательным: используем фолбэк на username или email, чтобы не нарушать ограничение NOT NULL до миграции
        $resolvedName = $validated['name']
            ?? ($validated['username'] ?? ($validated['email'] ?? ''));

        $user = User::create([
            'name' => $resolvedName,
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($passwordPlain),
            'role' => $validated['role'] ?? null,
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Пользователь создан')
            ->with('generated_password', $passwordPlain)
            ->with('generated_password_email', $user->email)
            ->with('generated_password_user_id', $user->id)
            ->with('generated_password_username', $user->username);
    }

    /**
     * Update a user's role (only 'viewer' or null).
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['nullable', 'in:viewer'],
        ]);

        $user->update([
            'role' => $validated['role'] ?? null,
        ]);

        return redirect()->route('settings.index')->with('success', 'Роль пользователя обновлена');
    }

    /**
     * Delete a user. Prevent deleting yourself.
     */
    public function deleteUser(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return redirect()->route('settings.index')->with('error', 'Нельзя удалить самого себя.');
        }

        $user->delete();

        return redirect()->route('settings.index')->with('success', 'Пользователь удалён.');
    }

    /**
     * Reset user's password: generate a new one, save hash, flash plain password for display.
     */
    public function resetUserPassword(Request $request, User $user): RedirectResponse
    {
        $passwordPlain = str()->password(12);
        $user->update([
            'password' => Hash::make($passwordPlain),
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Пароль пользователя сброшен')
            ->with('generated_password', $passwordPlain)
            ->with('generated_password_email', $user->email)
            ->with('generated_password_user_id', $user->id)
            ->with('generated_password_username', $user->username);
    }
}