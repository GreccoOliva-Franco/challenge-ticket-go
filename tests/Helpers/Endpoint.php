<?php

namespace Tests\Helpers;

function endpointBuilder(string $endpoint) {
    return function (string $extend = '') use ($endpoint) {
        return $endpoint . $extend;
    };
}