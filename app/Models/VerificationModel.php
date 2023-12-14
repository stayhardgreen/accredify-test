<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationModel extends Model
{
    const TABLE_NAME = 'verification';
    const FIELD_USER_ID = 'user_id';
    const FIELD_FILE_TYPE = 'file_type';
    const FIELD_FILE_PATH = 'file_path';
    const FIELD_VERIFICATION_RESULT = 'verification_result';
    const FIELD_ISSUER_NAME = 'issuer_name';

    protected $table = self::TABLE_NAME;

}
