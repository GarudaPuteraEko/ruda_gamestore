<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

return [
    'admin' => AdminMiddleware::class,
    'user' => UserMiddleware::class,
];
