<?php

    namespace App\Exceptions;

    use Exception;

    class BillingException extends Exception {
        const GENERAL_ERROR = 0;
        const NOT_ENOUGH = 1;
        const BAD_SIGNATURE = 2;
        const UNABLE_TO_CREATE_INVOICE = 3;
        const INVOICE_NOT_FOUND = 4;
        const UNABLE_TO_CREATE_TARIFF = 5;

    }