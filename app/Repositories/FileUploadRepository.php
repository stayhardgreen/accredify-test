<?php

namespace App\Repositories;

use App\DTO\JsonFileUploadDTO;
use App\Exceptions\RecipientException;
use App\Exceptions\ValidateIssuerException;
use App\Exceptions\ValidateSignatureException;
use App\Models\VerificationModel;
use App\Traits\UploadFileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileUploadRepository implements FileUploadRepositoryInterface
{
    use UploadFileService;

    private readonly string $uploadedPath;
    private readonly string $uploadDriver;
    public readonly array $loadJsonDataToArr;
    private VerificationModel $verificationModel;
    public function __construct()
    {
        $this->uploadDriver = 'public';
    }

    public function uploadAndSaveFile(JsonFileUploadDTO &$jsonFileUploadDTO): VerificationModel
    {
        try {
            $this
                ->uploadFileOnServer($jsonFileUploadDTO->file)
                ->loadJsonFileInArray()
                ->saveFileToTable()
                ->validateUploadedJsonFile($jsonFileUploadDTO);
            $result = 'verified';
        } catch (RecipientException $exception){
            $result = 'invalid_recipient';
        } catch (ValidateIssuerException $exception){
            $result = 'invalid_issuer';
        } catch (ValidateSignatureException $exception){
            $result = 'invalid_signature';
        }
        $this->verificationModel->{VerificationModel::FIELD_VERIFICATION_RESULT} = $result;
        $this->verificationModel->save();

        return $this->verificationModel;
    }

    private function uploadFileOnServer($file): self
    {
        $this->uploadedPath = $this->uploadFile(
            $file,
            'jsonfile',
            $this->uploadDriver
        );

        return $this;
    }

    private function saveFileToTable(): self
    {
        $this->verificationModel = new VerificationModel();
        $this->verificationModel->{VerificationModel::FIELD_FILE_TYPE} = 'json';
        $this->verificationModel->{VerificationModel::FIELD_USER_ID} = Auth::user()->id;
        $this->verificationModel->{VerificationModel::FIELD_FILE_PATH} = $this->uploadedPath;
        $this->verificationModel->{VerificationModel::FIELD_ISSUER_NAME} = getIssuerName($this->loadJsonDataToArr);
        $this->verificationModel->save();
        return $this;
    }

    private function loadJsonFileInArray(): self
    {
        $this->loadJsonDataToArr = Storage::drive($this->uploadDriver)->json($this->uploadedPath);
        return $this;
    }

    /**
     * @throws RecipientException
     * @throws ValidateIssuerException
     * @throws ValidateSignatureException
     */
    private function validateUploadedJsonFile(JsonFileUploadDTO &$jsonFileUploadDTO): void
    {
        $jsonFileUploadDTO
            ->jsonConvertedData($this->loadJsonDataToArr)
            ->validateRecipient()
            ->validateIssuer()
            ->validateSignature();
    }
}
