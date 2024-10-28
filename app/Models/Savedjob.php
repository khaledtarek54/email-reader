<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Savedjob extends Model
{
    protected $fillable = [
        'mail_id',
        'source_language',
        'target_language',
        'job_type',
        'workflow',
        'amount',
        'unit',
        'start_date',
        'delivery_time',
        'delivery_timezone',
        'shared_instructions',
        'unit_price',
        'currency',
        'in_folder',
        'instructions_folder',
        'reference_folder',
        'online_source_files',
        'content_type',
        'subject_matter',
        'auto_plan_strategy',
        'auto_assignment',
        'selection_plan',
    ];
}
