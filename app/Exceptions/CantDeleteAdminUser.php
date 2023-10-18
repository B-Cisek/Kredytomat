<?php

namespace App\Exceptions;

use Exception;

class CantDeleteAdminUser extends Exception
{
    protected $message = 'Cannot delete Admin user';
}
