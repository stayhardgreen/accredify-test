<?php

namespace App\Http\Controllers;

use App\Exceptions\RecipientException;
use App\Exceptions\ValidateIssuerException;
use App\Http\Requests\Upload\JsonFileUploadRequest;
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

        try {
            $this->fileUploadRepository->uploadAndSaveFile($jsonFileUploadDTO);
        } catch (RecipientException $exception){
            return response()->json([
                'status' => false,
                'message' => 'recipient must have name and email',
                'errorCode' => 'invalid_recipient'
            ], 200);
        } catch (ValidateIssuerException $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'errorCode' => 'invalid_issuer'
            ], 200);
        }

    }
}
