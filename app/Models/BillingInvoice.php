<?php

    namespace App\Models;

    class BillingInvoice extends CachedModel {
        protected $table = 'billing_invoices';
        public $timestamps = false;
        protected $guarded = array('id');

    }