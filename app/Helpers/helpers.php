<?php

function logActivity($Add, $grade, $request, $user, $msg)
{
    activity($Add)
        ->performedOn($grade)
        ->causedBy($user)
        ->withProperties([
            'ip_address' => $request->ip(),
            'updated_at' => now()->toDateTimeString()
        ])
        ->log($msg);
}

if (!function_exists('generate_slug')) {
    function generate_slug()
    {
        return 'SLUG-' . rand(1000, 9999);
    }
}
