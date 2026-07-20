<?php

it('serves the welcome page', function () {
    $this->get('/')->assertOk();
});

it('reports healthy on the framework health endpoint', function () {
    $this->get('/up')->assertOk();
});
