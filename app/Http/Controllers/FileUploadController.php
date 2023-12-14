<?php

namespace App\Http\Controllers;

use App\Exceptions\RecipientException;
use App\Exceptions\ValidateIssuerException;
use App\Exceptions\ValidateSignatureException;
use App\Http\Requests\Upload\JsonFileUploadRequest;
use App\Http\Resources\Verification\VerificationResource;
use App\Repositories\FileUploadRepositoryInterface;
use App\Services\UploadFile;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function __construct(
        public FileUploadRepositoryInterface $fileUploadRepository
    )
    {}
    public function uploadJsonFile(JsonFileUploadRequest $fileUploadReq)
    {
        $jsonFileUploadDTO = $fileUploadReq->data();
        return (new VerificationResource(
            $this->fileUploadRepository->uploadAndSaveFile($jsonFileUploadDTO)
        ))->response()->setStatusCode(200);
    }
}
