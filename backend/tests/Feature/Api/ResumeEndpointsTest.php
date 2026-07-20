<?php

use App\Models\Education;
use App\Models\Experience;

describe('GET /api/v1/experience', function () {
    it('returns ordered experience with array fields defaulting to empty', function () {
        $older = Experience::factory()->create(['sort' => 0, 'start_date' => '2020-01-01', 'achievements' => null, 'stack' => null]);
        $newer = Experience::factory()->current()->create(['sort' => 0, 'start_date' => '2024-06-01']);

        $response = $this->getJson('/api/v1/experience')->assertOk();

        expect(collect($response->json('data'))->pluck('id')->all())->toBe([$newer->id, $older->id]);

        $response->assertJsonPath('data.0.current', true)
            ->assertJsonPath('data.1.achievements', [])
            ->assertJsonPath('data.1.stack', [])
            ->assertJsonStructure(['data' => [[
                'id', 'role', 'company', 'company_url', 'location',
                'start_date', 'end_date', 'current', 'description', 'achievements', 'stack',
            ]]]);
    });

    it('serialises dates as Y-m-d', function () {
        Experience::factory()->create(['start_date' => '2022-03-15', 'end_date' => '2023-09-30']);

        $this->getJson('/api/v1/experience')
            ->assertOk()
            ->assertJsonPath('data.0.start_date', '2022-03-15')
            ->assertJsonPath('data.0.end_date', '2023-09-30');
    });
});

describe('GET /api/v1/education', function () {
    it('returns ordered education rows', function () {
        $second = Education::factory()->create(['sort' => 2]);
        $first = Education::factory()->create(['sort' => 1]);

        $response = $this->getJson('/api/v1/education')->assertOk();

        expect(collect($response->json('data'))->pluck('id')->all())->toBe([$first->id, $second->id]);

        $response->assertJsonStructure(['data' => [[
            'id', 'school', 'degree', 'field', 'start_date', 'end_date', 'description',
        ]]]);
    });
});
