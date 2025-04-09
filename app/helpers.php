<?php

use Illuminate\Support\Facades\Auth;

function isAdmin(): bool {
    return Auth::check() && Auth::user()->role === 'admin';
}

function isHR(): bool {
    return Auth::check() && Auth::user()->role === 'hr';
}
// function isUser(): bool {
//     return Auth::check() && Auth::user()->role === 'user';
// }
// function isSuperAdmin(): bool {
//     return Auth::check() && Auth::user()->role === 'superadmin';
// }
// function isAuthenticated(): bool {
//     return Auth::check();
// }
// function isGuest(): bool {
//     return !Auth::check();
// }
// function isAdminOrHR(): bool {
//     return Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'hr');
// }
