<?php

use App\Repositories\Contracts\WishlistRepositoryInterface;
use App\Services\WishlistService;

beforeEach(function () {
    $this->repo = Mockery::mock(WishlistRepositoryInterface::class);
    $this->service = new WishlistService($this->repo);
});

it('adds product to wishlist if not exists', function () {
    $this->repo->shouldReceive('exists')->once()->andReturn(false);
    $this->repo->shouldReceive('create')->once();

    $result = $this->service->toggle(1, 10);

    expect($result)->toBeTrue();
});

it('removes product from wishlist if exists', function () {
    $this->repo->shouldReceive('exists')->once()->andReturn(true);
    $this->repo->shouldReceive('deleteByUserAndProduct')->once();

    $result = $this->service->toggle(1, 10);

    expect($result)->toBeFalse();
});