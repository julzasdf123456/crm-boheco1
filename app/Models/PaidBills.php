<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaidBills
 * @package App\Models
 * @version February 7, 2023, 10:38 am PST
 *
 * @property string $AccountNumber
 * @property string $BillNumber
 * @property string|\Carbon\Carbon $ServicePeriodEnd
 * @property float $Power
 * @property float $Meter
 * @property float $PR
 * @property float $Others
 * @property float $NetAmount
 * @property string $PaymentType
 * @property string $ORNumber
 * @property string $Teller
 * @property string $DCRNumber
 * @property string|\Carbon\Carbon $PostingDate
 * @property float $PostingSequence
 * @property float $PromptPayment
 * @property float $Surcharge
 * @property float $SLAdjustment
 * @property float $OtherDeduction
 * @property string|\Carbon\Carbon $ORDate
 * @property float $MDRefund
 * @property string $Form2306
 * @property string $Form2307
 * @property float $Amount2306
 * @property float $Amount2307
 * @property float $ServiceFee
 * @property float $Others1
 * @property float $Others2
 */
class PaidBills extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'PaidBills';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvbilling";

    protected $primaryKey = ['AccountNumber', 'ServicePeriodEnd'];

    public $incrementing = false;

    public $fillable = [
        'AccountNumber',
        'BillNumber',
        'ServicePeriodEnd',
        'Power',
        'Meter',
        'PR',
        'Others',
        'NetAmount',
        'PaymentType',
        'ORNumber',
        'Teller',
        'DCRNumber',
        'PostingDate',
        'PostingSequence',
        'PromptPayment',
        'Surcharge',
        'SLAdjustment',
        'OtherDeduction',
        'ORDate',
        'MDRefund',
        'Form2306',
        'Form2307',
        'Amount2306',
        'Amount2307',
        'ServiceFee',
        'Others1',
        'Others2'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'AccountNumber' => 'string',
        'BillNumber' => 'string',
        'ServicePeriodEnd' => 'datetime',
        'Power' => 'float',
        'Meter' => 'float',
        'PR' => 'float',
        'Others' => 'float',
        'NetAmount' => 'float',
        'PaymentType' => 'string',
        'ORNumber' => 'string',
        'Teller' => 'string',
        'DCRNumber' => 'string',
        'PostingDate' => 'datetime',
        'PostingSequence' => 'float',
        'PromptPayment' => 'float',
        'Surcharge' => 'float',
        'SLAdjustment' => 'float',
        'OtherDeduction' => 'float',
        'ORDate' => 'datetime',
        'MDRefund' => 'float',
        'Form2306' => 'string',
        'Form2307' => 'string',
        'Amount2306' => 'float',
        'Amount2307' => 'float',
        'ServiceFee' => 'float',
        'Others1' => 'float',
        'Others2' => 'float',
        'id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'required|string|max:20',
        'BillNumber' => 'nullable|string|max:10',
        'ServicePeriodEnd' => 'required',
        'Power' => 'nullable|float',
        'Meter' => 'nullable|float',
        'PR' => 'nullable|float',
        'Others' => 'nullable|float',
        'NetAmount' => 'nullable|float',
        'PaymentType' => 'nullable|string|max:20',
        'ORNumber' => 'nullable|string|max:20',
        'Teller' => 'nullable|string|max:50',
        'DCRNumber' => 'nullable|string|max:20',
        'PostingDate' => 'nullable',
        'PostingSequence' => 'nullable|float',
        'PromptPayment' => 'nullable|float',
        'Surcharge' => 'nullable|float',
        'SLAdjustment' => 'nullable|float',
        'OtherDeduction' => 'nullable|float',
        'ORDate' => 'nullable',
        'MDRefund' => 'nullable|float',
        'Form2306' => 'nullable|string|max:50',
        'Form2307' => 'nullable|string|max:50',
        'Amount2306' => 'nullable|float',
        'Amount2307' => 'nullable|float',
        'ServiceFee' => 'nullable|float',
        'Others1' => 'nullable|float',
        'Others2' => 'nullable|float'
    ];

    
}
