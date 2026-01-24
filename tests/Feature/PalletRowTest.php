<?php

namespace Tests\Feature;

use App\Models\Pallet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PalletRowTest extends TestCase
{
    use RefreshDatabase;

    private function createEditorUser(): User
    {
        /** @var User $user */
        $user = User::factory()->create([
            'role' => null,
        ]);
        return $user;
    }

    public function test_pallet_can_be_created_with_row(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-001',
            'row' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('pallets', [
            'order_number' => 'TEST-001',
            'row' => 5,
        ]);
    }

    public function test_pallet_can_be_created_without_row(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-002',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('pallets', [
            'order_number' => 'TEST-002',
            'row' => null,
        ]);
    }

    public function test_pallet_row_can_be_updated(): void
    {
        $user = $this->createEditorUser();
        $pallet = Pallet::create([
            'number' => 'P-001',
            'order_number' => 'TEST-003',
            'status' => Pallet::STATUS_IN_WAREHOUSE,
        ]);

        $response = $this->actingAs($user)->put(route('pallet.update', $pallet), [
            'number' => 'P-001',
            'order_number' => 'TEST-003',
            'row' => 10,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('pallets', [
            'id' => $pallet->id,
            'row' => 10,
        ]);
    }

    public function test_pallet_row_can_be_removed(): void
    {
        $user = $this->createEditorUser();
        $pallet = Pallet::create([
            'number' => 'P-002',
            'order_number' => 'TEST-004',
            'status' => Pallet::STATUS_IN_WAREHOUSE,
            'row' => 7,
        ]);

        $response = $this->actingAs($user)->put(route('pallet.update', $pallet), [
            'number' => 'P-002',
            'order_number' => 'TEST-004',
            'row' => null,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $pallet->refresh();
        $this->assertNull($pallet->row);
        
        $this->assertDatabaseHas('pallets', [
            'id' => $pallet->id,
            'row' => null,
        ]);
    }

    public function test_row_validation_rejects_negative_numbers(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-005',
            'row' => -1,
        ]);

        $response->assertSessionHasErrors('row');
    }

    public function test_row_validation_rejects_zero(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-006',
            'row' => 0,
        ]);

        $response->assertSessionHasErrors('row');
    }

    public function test_row_validation_rejects_non_integer(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-007',
            'row' => 'not-a-number',
        ]);

        $response->assertSessionHasErrors('row');
    }

    public function test_row_validation_accepts_positive_integer(): void
    {
        $user = $this->createEditorUser();
        
        $response = $this->actingAs($user)->post(route('pallet.store'), [
            'order_number' => 'TEST-008',
            'row' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('pallets', [
            'order_number' => 'TEST-008',
            'row' => 1,
        ]);
    }

    public function test_dashboard_can_filter_pallets_by_row(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        
        Pallet::create([
            'number' => 'P-100',
            'status' => Pallet::STATUS_IN_WAREHOUSE,
            'row' => 5,
        ]);
        
        Pallet::create([
            'number' => 'P-101',
            'status' => Pallet::STATUS_IN_WAREHOUSE,
            'row' => 10,
        ]);
        
        Pallet::create([
            'number' => 'P-102',
            'status' => Pallet::STATUS_IN_WAREHOUSE,
            'row' => null,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard', ['filter_row' => 5]));

        $response->assertOk();
        $response->assertSee('P-100', false);
        $response->assertDontSee('P-101', false);
        $response->assertDontSee('P-102', false);
    }

    public function test_pallet_model_includes_row_in_fillable_attributes(): void
    {
        $pallet = new Pallet();
        $fillable = $pallet->getFillable();
        
        $this->assertContains('row', $fillable);
    }
}

