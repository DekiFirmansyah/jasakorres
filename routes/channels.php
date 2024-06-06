<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('letter-validation', function ($user) {
    return true; // Add your authorization logic here
});

Broadcast::channel('request-letter-code', function ($user) {
    return true; // Add your authorization logic here
});

Broadcast::channel('update-letter', function ($user) {
    return true; // Add your authorization logic here
});