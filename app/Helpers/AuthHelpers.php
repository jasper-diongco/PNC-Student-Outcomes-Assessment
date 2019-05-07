<?php
    use Illuminate\Support\Facades\Auth;

    function routeGuard() {
        if(Auth::check()) {
            return abort(401, 'Unauthorized');
        }
    }