<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionTotalPayments
 * @package App\Models
 * @version August 19, 2021, 5:53 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $SubTotal
 * @property string $Form2307TwoPercent
 * @property string $Form2307FivePercent
 * @property string $TotalVat
 * @property string $Total
 * @property string $Notes
 */
class ServiceConnectionTotalPayments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionTotalPayments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;


    public $fillable = [
        'id',
        'ServiceConnectionId',
        'SubTotal',
        'Form2307TwoPercent',
        'Form2307FivePercent',
        'TotalVat',
        'Total',
        'Notes',
        'ServiceConnectionFee',
        'BillDeposit',
        'WitholdableVat',
        'LaborCharge',
        'BOHECOShare',
        'Particulars',
        'BOHECOShareOnly',
        'ElectricianShareOnly',
        'MaterialCost',
        'LaborCost',
        'ContingencyCost',
        'MaterialsVAT',
        'TransformerCost',
        'TransformerVAT',
        'TransformerDownpaymentPercentage',
        'BillOfMaterialsTotal',
        'InstallationFeeCanBePaid',
        'InstallationFeeORNumber',
        'InstallationFeeORDate',
        'TransformerReceivablesTotal',
        'TransformerAmmortizationTerms',
        'TransformerAmmortizationStart',
        'TransformerORDate',
        'TransformerORNumber',
        'TransformerInterestPercentage',
        'WithholdingTwoPercent', // MATERIALS 1%
        'WithholdingFivePercent', // MATERIALS
        'InstallationFeeDownPaymentPercentage',
        'InstallationFeeBalance',
        'InstallationFeeTerms',
        'InstallationFeeTermAmountPerMonth',
        'InstallationPartial',
        'RemittanceForwarded',
        'InstallationForwarded',
        'TransformerForwarded',
        'TransformerTwoPercentWT', // Transformers 1%
        'TransformerFivePercentWT',
        'Item1', // LABOR WT 2%
        'Item2',
        'Item3',
        'Item4',
        'Item5',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'SubTotal' => 'string',
        'Form2307TwoPercent' => 'string',
        'Form2307FivePercent' => 'string',
        'TotalVat' => 'string',
        'Total' => 'string',
        'Notes' => 'string',
        'ServiceConnectionFee' => 'string',
        'BillDeposit' => 'string',
        'WitholdableVat' => 'string',
        'LaborCharge' => 'string',
        'BOHECOShare' => 'string',
        'Particulars' => 'string',
        'BOHECOShareOnly' => 'string',
        'ElectricianShareOnly' => 'string',
        'MaterialCost' => 'string',
        'LaborCost' => 'string',
        'ContingencyCost' => 'string',
        'MaterialsVAT' => 'string',
        'TransformerCost' => 'string',
        'TransformerVAT' => 'string',
        'TransformerDownpaymentPercentage' => 'string',
        'BillOfMaterialsTotal' => 'string',
        'InstallationFeeCanBePaid' => 'string',
        'InstallationFeeORNumber' => 'string',
        'InstallationFeeORDate' => 'string',
        'TransformerReceivablesTotal' => 'string',
        'TransformerAmmortizationTerms' => 'string',
        'TransformerAmmortizationStart' => 'string',
        'TransformerORDate' => 'string',
        'TransformerORNumber' => 'string',
        'TransformerInterestPercentage' => 'string',
        'WithholdingTwoPercent' => 'string',
        'WithholdingFivePercent' => 'string',
        'InstallationFeeDownPaymentPercentage' => 'string',
        'InstallationFeeBalance' => 'string',
        'InstallationFeeTerms' => 'string',
        'InstallationFeeTermAmountPerMonth' => 'string',
        'InstallationPartial' => 'string',
        'RemittanceForwarded' => 'string',
        'InstallationForwarded' => 'string',
        'TransformerForwarded' => 'string',
        'TransformerTwoPercentWT' => 'string',
        'TransformerFivePercentWT' => 'string',
        'Item1' => 'string',
        'Item2' => 'string',
        'Item3' => 'string',
        'Item4' => 'string',
        'Item5' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'SubTotal' => 'nullable|string|max:60',
        'Form2307TwoPercent' => 'nullable|string|max:60',
        'Form2307FivePercent' => 'nullable|string|max:60',
        'TotalVat' => 'nullable|string|max:60',
        'Total' => 'nullable|string|max:60',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ServiceConnectionFee' => 'nullable|string',
        'BillDeposit' => 'nullable|string',
        'WitholdableVat' => 'nullable|string',
        'LaborCharge' => 'nullable|string',
        'BOHECOShare' => 'nullable|string',
        'Particulars' => 'nullable|string',
        'BOHECOShareOnly' => 'nullable|string',
        'ElectricianShareOnly' => 'nullable|string',
        'MaterialCost' => 'nullable|string',
        'LaborCost' => 'nullable|string',
        'ContingencyCost' => 'nullable|string',
        'MaterialsVAT' => 'nullable|string',
        'TransformerCost' => 'nullable|string',
        'TransformerVAT' => 'nullable|string',
        'TransformerDownpaymentPercentage' => 'nullable|string',
        'BillOfMaterialsTotal' => 'nullable|string',
        'InstallationFeeCanBePaid' => 'nullable|string',
        'InstallationFeeORNumber' => 'nullable|string',
        'InstallationFeeORDate' => 'nullable|string',
        'TransformerReceivablesTotal' => 'nullable|string',
        'TransformerAmmortizationTerms' => 'nullable|string',
        'TransformerAmmortizationStart' => 'nullable|string',
        'TransformerORDate' => 'nullable|string',
        'TransformerORNumber' => 'nullable|string',
        'TransformerInterestPercentage' => 'nullable|string',
        'WithholdingTwoPercent' => 'nullable|string',
        'WithholdingFivePercent' => 'nullable|string',
        'InstallationFeeDownPaymentPercentage' => 'nullable|string',
        'InstallationFeeBalance' => 'nullable|string',
        'InstallationFeeTerms' => 'nullable|string',
        'InstallationFeeTermAmountPerMonth' => 'nullable|string',
        'InstallationPartial' => 'nullable|string',
        'RemittanceForwarded' => 'nullable|string',
        'InstallationForwarded' => 'nullable|string',
        'TransformerForwarded' => 'nullable|string',
        'TransformerTwoPercentWT' => 'nullable|string',
        'TransformerFivePercentWT' => 'nullable|string',
        'Item1' => 'null|string',
        'Item2' => 'null|string',
        'Item3' => 'null|string',
        'Item4' => 'null|string',
        'Item5' => 'null|string',
    ];

    
}
