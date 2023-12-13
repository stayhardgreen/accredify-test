<?php

namespace App\Repositories;

use App\DTO\JsonFileUploadDTO;

interface FileUploadRepositoryInterface
{
    public function uploadAndSaveFile(JsonFileUploadDTO &$jsonFileUploadDTO);
}
