<?php

    namespace App\Exceptions;

    use Exception;

    class RatingException extends Exception {
        const GENERAL_ERROR = 0;
        const NOT_ENOUGH = 1;
        const BAD_SIGNATURE = 2;

    }