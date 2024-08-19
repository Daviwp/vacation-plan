<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\VacationPlan;
use Illuminate\Support\Facades\Artisan;

class VacationPlanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Artisan::call('migrate'); // Remover se usando RefreshDatabase
    }

    protected function getAuthorizationHeader(): array
    {
        return [
            'Authorization' => 'Bearer ' . env('API_TEST_TOKEN')
        ];
    }

    /** @test */
    public function it_creates_a_vacation_plan()
    {
        $response = $this->postJson('/api/vacation-plans', [
            'title' => 'Trip to Paris',
            'description' => 'A wonderful trip to Paris.',
            'date' => '2024-08-19',
            'location' => 'Paris, France',
            'participants' => ['John Doe', 'Jane Smith'],
        ], $this->getAuthorizationHeader());

        $response->assertStatus(201)
                 ->assertJson([
                     'title' => 'Trip to Paris',
                     'description' => 'A wonderful trip to Paris.',
                     'date' => '2024-08-19',
                     'location' => 'Paris, France',
                     'participants' => ['John Doe', 'Jane Smith'],
                 ]);
    }

    /** @test */
    public function it_retrieves_a_vacation_plan()
    {
        $plan = VacationPlan::factory()->create();

        $response = $this->getJson("/api/vacation-plans/{$plan->id}", $this->getAuthorizationHeader());

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $plan->id,
                     'title' => $plan->title,
                     'description' => $plan->description,
                     'date' => $plan->date,
                     'location' => $plan->location,
                     'participants' => $plan->participants,
                 ]);
    }

    /** @test */
    public function it_updates_a_vacation_plan()
    {
        $plan = VacationPlan::factory()->create();

        $response = $this->putJson("/api/vacation-plans/{$plan->id}", [
            'title' => 'Updated Trip',
            'description' => 'Updated description.',
            'date' => '2024-08-20',
            'location' => 'London, UK',
            'participants' => ['John Doe'],
        ], $this->getAuthorizationHeader());

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $plan->id,
                     'title' => 'Updated Trip',
                     'description' => 'Updated description.',
                     'date' => '2024-08-20',
                     'location' => 'London, UK',
                     'participants' => ['John Doe'],
                 ]);
    }

    /** @test */
    public function it_deletes_a_vacation_plan()
    {
        $plan = VacationPlan::factory()->create();

        $response = $this->deleteJson("/api/vacation-plans/{$plan->id}", [], $this->getAuthorizationHeader());

        $response->assertStatus(204);
    }
}
