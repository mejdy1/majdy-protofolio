<?php

use App\Enums\SkillLevel;
use App\Models\Skill;

it('groups skills by category', function () {
    Skill::factory()->count(2)->create(['category' => 'Languages', 'sort' => 0]);
    Skill::factory()->create(['category' => 'Tools', 'sort' => 1]);

    $response = $this->getJson('/api/v1/skills')->assertOk();
    $groups = collect($response->json('data'));

    expect($groups)->toHaveCount(2)
        ->and($groups->pluck('category')->all())->toContain('Languages', 'Tools')
        ->and($groups->firstWhere('category', 'Languages')['items'])->toHaveCount(2);
});

it('shapes items with proficiency value and percentage', function (SkillLevel $level, int $pct) {
    Skill::factory()->level($level)->create(['category' => 'Languages']);

    $this->getJson('/api/v1/skills')
        ->assertOk()
        ->assertJsonPath('data.0.items.0.proficiency', $level->value)
        ->assertJsonPath('data.0.items.0.proficiency_pct', $pct)
        ->assertJsonStructure(['data' => [[
            'category',
            'items' => [['id', 'name', 'icon', 'category', 'proficiency', 'proficiency_pct', 'years_experience', 'color']],
        ]]]);
})->with([
    'beginner' => [SkillLevel::Beginner, 35],
    'intermediate' => [SkillLevel::Intermediate, 60],
    'advanced' => [SkillLevel::Advanced, 82],
    'expert' => [SkillLevel::Expert, 95],
]);

it('orders items by sort, category, name before grouping', function () {
    Skill::factory()->create(['category' => 'A', 'name' => 'zeta', 'sort' => 0]);
    Skill::factory()->create(['category' => 'A', 'name' => 'alpha', 'sort' => 0]);

    $names = collect($this->getJson('/api/v1/skills')->json('data.0.items'))->pluck('name')->all();

    expect($names)->toBe(['alpha', 'zeta']);
});
