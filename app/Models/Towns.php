<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Towns
 * @package App\Models
 * @version July 16, 2021, 9:12 am UTC
 *
 * @property string $Town
 * @property string $District
 * @property string $Station
 */
class Towns extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Towns';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Town',
        'District',
        'Station'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Town' => 'string',
        'District' => 'string',
        'Station' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Town' => 'nullable|string|max:300',
        'District' => 'nullable|string|max:300',
        'Station' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function parseTownCode($townCode) {
        if($townCode === "02") {
            return "CLARIN";
        } elseif($townCode === "01") {
            return "TUBIGON";
        } elseif($townCode === "08") {
            return "INABANGA";
        } elseif($townCode === "03") {
            return "SAGBAYAN";
        } elseif($townCode === "07") {
            return "LOON";
        } elseif($townCode === "13") {
            return "MARIBOJOC";
        } elseif($townCode === "22") {
            return "BALILIHAN";
        } elseif($townCode === "04") {
            return "CATIGBIAN";
        } elseif($townCode === "05") {
            return "SAN ISIDRO";
        } elseif($townCode === "24") {
            return "CORELLA";
        } elseif($townCode === "18") {
            return "CORTES";
        } elseif($townCode === "25") {
            return "SEVILLA";
        } elseif($townCode === "21") {
            return "SIKATUNA";
        } elseif($townCode === "12") {
            return "ANTEQUERA";
        } elseif($townCode === "06") {
            return "CALAPE";
        } elseif($townCode === "23") {
            return "ALBUR";
        } elseif($townCode === "14") {
            return "BACLAYON";
        } elseif($townCode === "16") {
            return "DAUIS";
        } elseif($townCode === "17") {
            return "PANGLAO";
        } elseif($townCode === "20") {
            return "DIMIAO";
        } elseif($townCode === "26") {
            return "LILA";
        } elseif($townCode === "15") {
            return "LOAY";
        } elseif($townCode === "19") {
            return "LOBOC";
        } elseif($townCode === "10") {
            return "BATUAN";
        } elseif($townCode === "11") {
            return "BILAR";
        } elseif($townCode === "09") {
            return "CARMEN";
        }
    }
}
