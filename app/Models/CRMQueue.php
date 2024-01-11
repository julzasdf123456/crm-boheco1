<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CRMDetails;
use App\Models\MemberConsumers;
use App\Models\CRMQueue;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionTimeframes;
use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CRMQueue
 * @package App\Models
 * @version June 8, 2023, 11:25 am PST
 *
 * @property \Illuminate\Database\Eloquent\Collection $cRMDetails
 * @property string $ConsumerName
 * @property string $ConsumerAddress
 * @property string $TransactionPurpose
 * @property string $Source
 * @property string $SourceId
 * @property number $SubTotal
 * @property number $VAT
 * @property number $Total
 */
class CRMQueue extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRMQueue';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrvaccounting";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ConsumerName',
        'ConsumerAddress',
        'TransactionPurpose',
        'Source',
        'SourceId',
        'SubTotal',
        'VAT',
        'Total'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ConsumerName' => 'string',
        'ConsumerAddress' => 'string',
        'TransactionPurpose' => 'string',
        'Source' => 'string',
        'SourceId' => 'string',
        'SubTotal' => 'decimal:2',
        'VAT' => 'decimal:2',
        'Total' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ConsumerName' => 'nullable|string|max:100',
        'ConsumerAddress' => 'nullable|string|max:200',
        'TransactionPurpose' => 'nullable|string|max:50',
        'Source' => 'nullable|string|max:50',
        'SourceId' => 'nullable|string|max:30',
        'SubTotal' => 'nullable|numeric',
        'VAT' => 'nullable|numeric',
        'Total' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cRMDetails()
    {
        return $this->hasMany(\App\Models\CRMDetail::class, 'ReferenceNo');
    }

    public static function saveMembershipFee($membershipObject, $membershipFee, $primerFee) {
        $queueId = IDGenerator::generateID() . '-M';

        // DELETE CRM DETAILS FIRST
        CRMDetails::where('ReferenceNo', $queueId)->delete();

        // SAVE FIRST CRM QUEUE
        $crmQ = CRMQueue::find($queueId);

        if ($crmQ == null) {
            $crmQ = new CRMQueue;
            $crmQ->id = $queueId;
            $crmQ->ConsumerName = MemberConsumers::serializeMemberName($membershipObject);
            $crmQ->ConsumerAddress = MemberConsumers::getAddress($membershipObject);
            $crmQ->TransactionPurpose = 'Membership Application Payment';
            $crmQ->SourceId = $membershipObject->Id;
        } else {
            $crmQ->ConsumerName = MemberConsumers::serializeMemberName($membershipObject);
            $crmQ->ConsumerAddress = MemberConsumers::getAddress($membershipObject);
            $crmQ->TransactionPurpose = 'Membership Application Payment';
            $crmQ->SourceId = $membershipObject->Id;
        }

        $total = 0;
        // SAVE CRMDETAILS
        // MEMBERSHIP FEE
        if ($membershipFee != null && $membershipFee > 0) {
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "41";
            $queuDetails->ReferenceNo = $queueId;
            $queuDetails->Particular = 'Membership fee';
            $queuDetails->GLCode = '31030100000';
            $queuDetails->Total = $membershipFee;
            $queuDetails->save();

            $total += $membershipFee;
        }

        // MEMBERSHIP FEE
        if ($primerFee != null && $primerFee > 0) {
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "52";
            $queuDetails->ReferenceNo = $queueId;
            $queuDetails->Particular = 'Primer-Charges 1';
            $queuDetails->GLCode = '53050200000';
            $queuDetails->Total = $primerFee;
            $queuDetails->save();

            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "63";
            $queuDetails->ReferenceNo = $queueId;
            $queuDetails->Particular = 'EVAT';
            $queuDetails->GLCode = '22420414001';
            $queuDetails->Total = $primerFee * .12;
            $queuDetails->save();

            $total += $primerFee + ($primerFee * .12);
        }

        $crmQ->Total = $total;
        $crmQ->save();
    }

    public static function saveInstallationFees($serviceConnection, $totalTransactions) {
        $scId = $serviceConnection->id;

        if ($totalTransactions != null) {
            $qId = $scId . '-I';
            $overAllTotal = 0;

            /**
             * CRM QUEUE
             */
            $queue = new CRMQueue;
            $queue->id = $qId;
            $queue->ConsumerName = $serviceConnection->ServiceAccountName;
            $queue->ConsumerAddress = ServiceConnections::getAddress($serviceConnection);
            $queue->TransactionPurpose = 'Material and Installation Fees';
            $queue->SourceId = $scId;

             /**
             * CRM QUEUE DETAILS
             */
            // MATERIAL COST
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "1";
            $queuDetails->ReferenceNo = $qId;
            $queuDetails->Particular = ServiceConnections::getInstallationFeeDescription($totalTransactions->MaterialCost);
            $queuDetails->GLCode = ServiceConnections::getInstallationFeeCode(floatval($totalTransactions->MaterialCost));
            $queuDetails->Total = $totalTransactions->MaterialCost;
            $queuDetails->save();
            $overAllTotal += floatval($totalTransactions->MaterialCost);

            // LABOR COST & CONTINGENCY
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "2";
            $queuDetails->ReferenceNo = $qId;
            $queuDetails->Particular = 'Installation fee Labor cost & contingency';
            $queuDetails->GLCode = '51250400000';
            $queuDetails->Total = floatval($totalTransactions->LaborCost) + floatval($totalTransactions->ContingencyCost);
            $queuDetails->save();
            $overAllTotal += floatval($totalTransactions->LaborCost) + floatval($totalTransactions->ContingencyCost);
            
            // MATERIALS VAT
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "3";
            $queuDetails->ReferenceNo = $qId;
            $queuDetails->Particular = 'EVAT';
            $queuDetails->GLCode = '22420414001';
            $queuDetails->Total = $totalTransactions->MaterialsVAT;
            $queuDetails->save();
            $overAllTotal += floatval($totalTransactions->MaterialsVAT);

            // IF HAS PROMISORY NOTE
            if ($totalTransactions->InstallationFeeBalance != null && $totalTransactions->InstallationFeeBalance > 0) {
                // PN FOR INSTLLATION FEE
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "4";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Receivable with PNs';
                $queuDetails->GLCode = '12300000000';
                $queuDetails->Total = '-' . $totalTransactions->InstallationFeeBalance;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->InstallationFeeBalance);
            }

            // IF HAS WITHHOlDINGS
            if ($totalTransactions->WithholdingTwoPercent != null && $totalTransactions->WithholdingTwoPercent > 0) {
                // 1%
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "5";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Prepayments - Others 2307';
                $queuDetails->GLCode = '12910111002';
                $queuDetails->Total = '-' . $totalTransactions->WithholdingTwoPercent;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->WithholdingTwoPercent);
            }

            
            if ($totalTransactions->Item1 != null && $totalTransactions->Item1 > 0) {
                // 2%
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "9";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Prepayments - Others 2307';
                $queuDetails->GLCode = '12910111002';
                $queuDetails->Total = '-' . $totalTransactions->Item1;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->Item1);
            }

            if ($totalTransactions->WithholdingFivePercent != null && $totalTransactions->WithholdingFivePercent > 0) {
                // 5%
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "6";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'EVAT 2306';
                $queuDetails->GLCode = '22420414002';
                $queuDetails->Total = '-' . $totalTransactions->WithholdingFivePercent;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->WithholdingFivePercent);
            }
            
            $queue->SubTotal = $overAllTotal - ($totalTransactions->MaterialsVAT);
            $queue->VAT = $totalTransactions->MaterialsVAT;
            $queue->Total = $overAllTotal;
            $queue->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $scId;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Installation Fees Forwarded to Cashier';
            $timeFrame->Notes = 'Installation Fees Forwarded to Cashier Manually';
            $timeFrame->save();

            $totalTransactions->InstallationForwarded = 'Yes';
            $totalTransactions->save();

            $queuesZero = CRMDetails::whereRaw("Total=0")->delete();
        }
    }

    public static function saveTransformerFees($serviceConnection, $totalTransactions) {
        $scId = $serviceConnection->id;

        if ($totalTransactions != null) {
            $qId = $scId . '-T';
            $overAllTotal = 0;

            /**
             * CRM QUEUE
             */
            $queue = new CRMQueue;
            $queue->id = $qId;
            $queue->ConsumerName = $serviceConnection->ServiceAccountName;
            $queue->ConsumerAddress = ServiceConnections::getAddress($serviceConnection);
            $queue->TransactionPurpose = 'Transformer Fees';
            $queue->SourceId = $scId;

             /**
             * CRM QUEUE DETAILS
             */
            // TRANSFORMER COST
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "1";
            $queuDetails->ReferenceNo = $qId;
            $queuDetails->Particular = $serviceConnection->LoadCategory . ' kVA Transformer';
            $queuDetails->GLCode = '23100000000';
            $queuDetails->Total = $totalTransactions->TransformerCost;
            $queuDetails->save();
            $overAllTotal += floatval($totalTransactions->TransformerCost);
            
            // TRANSFORMER VAT
            $queuDetails = new CRMDetails;
            $queuDetails->id = IDGenerator::generateID() . "2";
            $queuDetails->ReferenceNo = $qId;
            $queuDetails->Particular = 'EVAT';
            $queuDetails->GLCode = '22420414001';
            $queuDetails->Total = $totalTransactions->TransformerVAT;
            $queuDetails->save();
            $overAllTotal += floatval($totalTransactions->TransformerVAT);

            // IF TRANSFOMER IS AMMORTIZED
            if ($totalTransactions->TransformerReceivablesTotal != null && $totalTransactions->TransformerReceivablesTotal > 0) {
                // PN FOR INSTLLATION FEE
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "3";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Transformer Receivables';
                $queuDetails->GLCode = '12610503000';
                $queuDetails->Total = '-' . $totalTransactions->TransformerReceivablesTotal;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->TransformerReceivablesTotal);
            }

            // IF HAS WITHHOlDINGS
            if ($totalTransactions->TransformerTwoPercentWT != null && $totalTransactions->TransformerTwoPercentWT > 0) {
                // 2%
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "4";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Prepayments - Others 2307';
                $queuDetails->GLCode = '12910111002';
                $queuDetails->Total = '-' . $totalTransactions->TransformerTwoPercentWT;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->TransformerTwoPercentWT);
            }

            if ($totalTransactions->TransformerFivePercentWT != null && $totalTransactions->TransformerFivePercentWT > 0) {
                // 5%
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "5";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'EVAT 2306';
                $queuDetails->GLCode = '22420414002';
                $queuDetails->Total = '-' . $totalTransactions->TransformerFivePercentWT;
                $queuDetails->save();
                $overAllTotal += -floatval($totalTransactions->TransformerFivePercentWT);
            }
            
            $queue->SubTotal = $overAllTotal - ($totalTransactions->MaterialsVAT);
            $queue->VAT = $totalTransactions->MaterialsVAT;
            $queue->Total = $overAllTotal;
            $queue->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $scId;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Transformer Fees Forwarded to Cashier';
            $timeFrame->Notes = 'Transformer Fees Forwarded to Cashier Manually';
            $timeFrame->save();

            $totalTransactions->TransformerForwarded = 'Yes';
            $totalTransactions->save();

            $queuesZero = CRMDetails::whereRaw("Total=0")->delete();
        }
    }

    public static function saveAllFees($serviceConnection, $totalTransactions) {
        $scId = $serviceConnection->id;

        if ($totalTransactions != null) {
            $qId = $scId . '-ALL';
            $overAllTotal = 0;

            /**
             * CRM QUEUE
             */
            $queue = new CRMQueue;
            $queue->id = $qId;
            $queue->ConsumerName = $serviceConnection->ServiceAccountName;
            $queue->ConsumerAddress = ServiceConnections::getAddress($serviceConnection);
            $queue->TransactionPurpose = 'Service Application Fees';
            $queue->SourceId = $scId;

            /**
             * ===========================================
             * REMITTANCE FEES
             * ===========================================
             */
            if ($totalTransactions->RemittanceForwarded == null) {
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "1";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Electrician Share';
                $queuDetails->GLCode = '12910201000';
                $queuDetails->Total = $totalTransactions->LaborCharge;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->LaborCharge);

                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "2";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'BOHECO I Share';
                $queuDetails->GLCode = '44040100000';
                $queuDetails->Total = $totalTransactions->BOHECOShare;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->BOHECOShare);

                // BILL & ENERGY DEPOSIT
                if (ServiceConnections::isResidentials($serviceConnection->AccountType)) {
                    // BILL DEPOSIT
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "3";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Bill Deposit';
                    $queuDetails->GLCode = '21720110002';
                    $queuDetails->Total = $totalTransactions->BillDeposit;
                    $queuDetails->save();
                    $overAllTotal += floatval($totalTransactions->BillDeposit);
                } else {
                    // ENERGY DEPOSIT
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "4";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Energy Deposit';
                    $queuDetails->GLCode = '21720110001';
                    $queuDetails->Total = $totalTransactions->BillDeposit;
                    $queuDetails->save();
                    $overAllTotal += floatval($totalTransactions->BillDeposit);
                }

                // 2307 2%
                if ($totalTransactions->Form2307TwoPercent != null && $totalTransactions->Form2307TwoPercent > 0) {
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "5";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Prepayments - Others 2307';
                    $queuDetails->GLCode = '12910111002';
                    $queuDetails->Total = '-' . $totalTransactions->Form2307TwoPercent;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->Form2307TwoPercent);
                }

                // 2307 5%
                if ($totalTransactions->Form2307FivePercent != null && $totalTransactions->Form2307FivePercent > 0) {
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "6";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'EVAT 2306';
                    $queuDetails->GLCode = '22420414002';
                    $queuDetails->Total = '-' . $totalTransactions->Form2307FivePercent;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->Form2307FivePercent);
                }

                // OTHER PAYABLES
                $serviceConnectionTransactions = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select(
                        'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                        'CRM_ServiceConnectionPaymentParticulars.Particular',
                        'CRM_ServiceConnectionPaymentParticulars.AccountNumber',
                    )
                    ->where('ServiceConnectionId', $scId)
                    ->get();
                
                $i=7;
                foreach ($serviceConnectionTransactions as $item) {
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() + $i;
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = $item->Particular;
                    $queuDetails->GLCode = $item->AccountNumber;
                    $queuDetails->Total = $item->Amount;
                    $queuDetails->save();
                    $overAllTotal += floatval($totalTransactions->Amount);
                    $i++;
                }

                // TOTAL VAT
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() + ($i+1);
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'EVAT';
                $queuDetails->GLCode = '22420414001';
                $queuDetails->Total = $totalTransactions->TotalVat;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->TotalVat);

                $totalTransactions->RemittanceForwarded = 'Yes';
            }

            /**
             * ===========================================
             * INSTALLATION FEES
             * ===========================================
             */
            if ($totalTransactions->InstallationForwarded == null) {
                // MATERIAL COST
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "21";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = ServiceConnections::getInstallationFeeDescription($totalTransactions->MaterialCost);
                $queuDetails->GLCode = ServiceConnections::getInstallationFeeCode(floatval($totalTransactions->MaterialCost));
                $queuDetails->Total = $totalTransactions->MaterialCost;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->MaterialCost);

                // LABOR COST & CONTINGENCY
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "22";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'Installation fee Labor cost & contingency';
                $queuDetails->GLCode = '51250400000';
                $queuDetails->Total = floatval($totalTransactions->LaborCost) + floatval($totalTransactions->ContingencyCost);
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->LaborCost) + floatval($totalTransactions->ContingencyCost);
                
                // MATERIALS VAT
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "23";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'EVAT';
                $queuDetails->GLCode = '22420414001';
                $queuDetails->Total = $totalTransactions->MaterialsVAT;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->MaterialsVAT);

                // IF HAS PROMISORY NOTE
                if ($totalTransactions->InstallationFeeBalance != null && $totalTransactions->InstallationFeeBalance > 0) {
                    // PN FOR INSTLLATION FEE
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "24";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Receivable with PNs';
                    $queuDetails->GLCode = '12300000000';
                    $queuDetails->Total = '-' . $totalTransactions->InstallationFeeBalance;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->InstallationFeeBalance);
                }

                // IF HAS WITHHOlDINGS
                if ($totalTransactions->WithholdingTwoPercent != null && $totalTransactions->WithholdingTwoPercent > 0) {
                    // 2%
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "25";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Prepayments - Others 2307';
                    $queuDetails->GLCode = '12910111002';
                    $queuDetails->Total = '-' . $totalTransactions->WithholdingTwoPercent;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->WithholdingTwoPercent);
                }

                if ($totalTransactions->Item1 != null && $totalTransactions->Item1 > 0) {
                    // 2%
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "35";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Prepayments - Others 2307';
                    $queuDetails->GLCode = '12910111002';
                    $queuDetails->Total = '-' . $totalTransactions->Item1;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->Item1);
                }

                if ($totalTransactions->WithholdingFivePercent != null && $totalTransactions->WithholdingFivePercent > 0) {
                    // 5%
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "26";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'EVAT 2306';
                    $queuDetails->GLCode = '22420414002';
                    $queuDetails->Total = '-' . $totalTransactions->WithholdingFivePercent;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->WithholdingFivePercent);
                }

                $totalTransactions->InstallationForwarded = 'Yes';
            }

            /**
             * ===========================================
             * TRANSFORMER FEES
             * ===========================================
             */
            if ($totalTransactions->TransformerForwarded == null) {
                // TRANSFORMER COST
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "31";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = $serviceConnection->LoadCategory . ' kVA Transformer';
                $queuDetails->GLCode = '23100000000';
                $queuDetails->Total = $totalTransactions->TransformerCost;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->TransformerCost);
                
                // TRANSFORMER VAT
                $queuDetails = new CRMDetails;
                $queuDetails->id = IDGenerator::generateID() . "32";
                $queuDetails->ReferenceNo = $qId;
                $queuDetails->Particular = 'EVAT';
                $queuDetails->GLCode = '22420414001';
                $queuDetails->Total = $totalTransactions->TransformerVAT;
                $queuDetails->save();
                $overAllTotal += floatval($totalTransactions->TransformerVAT);

                // IF TRANSFOMER IS AMMORTIZED
                if ($totalTransactions->TransformerReceivablesTotal != null && $totalTransactions->TransformerReceivablesTotal > 0) {
                    // PN FOR INSTLLATION FEE
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "33";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Transformer Receivables';
                    $queuDetails->GLCode = '12610503000';
                    $queuDetails->Total = '-' . $totalTransactions->TransformerReceivablesTotal;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->TransformerReceivablesTotal);
                }

                // IF HAS WITHHOlDINGS
                if ($totalTransactions->TransformerTwoPercentWT != null && $totalTransactions->TransformerTwoPercentWT > 0) {
                    // 2%
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "34";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'Prepayments - Others 2307';
                    $queuDetails->GLCode = '12910111002';
                    $queuDetails->Total = '-' . $totalTransactions->TransformerTwoPercentWT;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->TransformerTwoPercentWT);
                }

                if ($totalTransactions->TransformerFivePercentWT != null && $totalTransactions->TransformerFivePercentWT > 0) {
                    // 5%
                    $queuDetails = new CRMDetails;
                    $queuDetails->id = IDGenerator::generateID() . "35";
                    $queuDetails->ReferenceNo = $qId;
                    $queuDetails->Particular = 'EVAT 2306';
                    $queuDetails->GLCode = '22420414002';
                    $queuDetails->Total = '-' . $totalTransactions->TransformerFivePercentWT;
                    $queuDetails->save();
                    $overAllTotal += -floatval($totalTransactions->TransformerFivePercentWT);
                }

                $totalTransactions->TransformerForwarded = 'Yes';
            }

            // $queue->SubTotal = $overAllTotal + floatval($totalTransactions->BillOfMaterialsTotal) + floatval($totalTransactions->BillOfMaterialsTotal);
            // $queue->VAT = $totalTransactions->MaterialsVAT;
            $queue->Total = $overAllTotal;
            $queue->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $scId;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'All Fees Forwarded to Cashier';
            $timeFrame->Notes = 'All Fees Forwarded to Cashier Manually';
            $timeFrame->save();

            
            $totalTransactions->save();

            $queuesZero = CRMDetails::whereRaw("Total=0")->delete();
        }
    }
}
