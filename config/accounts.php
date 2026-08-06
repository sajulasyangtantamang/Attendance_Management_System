<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Account Password
    |--------------------------------------------------------------------------
    |
    | Newly created teacher and student accounts are assigned this password
    | instead of one typed in by the admin. It's emailed to the user via
    | App\Notifications\AccountCreated and they are forced to change it
    | (see App\Http\Middleware\EnsurePasswordIsChanged) the first time they
    | log in.
    |
    */

    'default_password' => env('DEFAULT_ACCOUNT_PASSWORD', 'Password@123'),

];
