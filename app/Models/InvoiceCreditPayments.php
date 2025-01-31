<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceCreditPayments extends Model
{
    use HasFactory;

    protected $connection = 'mysql_billing';
    protected $table = 'invoice_credit_payments';
}
