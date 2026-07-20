<?php

use App\Enums\SkillLevel;

it('maps each level to its proficiency percentage', function (SkillLevel $level, int $pct) {
    expect($level->percentage())->toBe($pct);
})->with([
    'beginner' => [SkillLevel::Beginner, 35],
    'intermediate' => [SkillLevel::Intermediate, 60],
    'advanced' => [SkillLevel::Advanced, 82],
    'expert' => [SkillLevel::Expert, 95],
]);

it('labels levels with an uppercase first letter', function () {
    foreach (SkillLevel::cases() as $level) {
        expect($level->getLabel())->toBe(ucfirst($level->value));
    }
});
