<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documents extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ruc',
        'razon_social',
        'tipo_documento',
        'employee_id',
        'serie',
        'correlativo',
        'archivo',
        'estado',
    ];
}
