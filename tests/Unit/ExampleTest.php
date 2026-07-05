<?php

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('that player logic properties are accessible via magic getter', function () {
    $player = new \App\Logic\Player(10, 10, 20, 20, 10, 10);
    expect($player->energyStart)->toBe(20);
    expect($player->skillStart)->toBe(10);
});
