<?php

namespace App\Repositories;

use App\DTO\JsonFileUploadDTO;
use App\Exceptions\RecipientException;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Storage;

class FileUploadRepository implements FileUploadRepositoryInterface
{
    private readonly string $uploadedPath;
    private readonly string $uploadDriver;
    public function __construct(public UploadFileService $uploadFileService)
    {
        $this->uploadDriver = 'public';
    }

    /**
     * @throws RecipientException
     */
    public function uploadAndSaveFile(JsonFileUploadDTO &$jsonFileUploadDTO): void
    {
        $this
            ->uploadFileOnServer($jsonFileUploadDTO->file)
            ->validateUploadedJsonFile($jsonFileUploadDTO);
    }

    private function uploadFileOnServer($file): self
    {
        $this->uploadedPath = $this->uploadFileService->uploadFile(
            $file,
            'jsonfile',
            $this->uploadDriver
        );

        return $this;
    }

    /**
     * @throws RecipientException
     */
    private function validateUploadedJsonFile(JsonFileUploadDTO &$jsonFileUploadDTO): void
    {
        $dataInArr = Storage::drive($this->uploadDriver)->json($this->uploadedPath);
        $jsonFileUploadDTO
            ->jsonConvertedData($dataInArr)
            ->validateRecipient()
            ->validateIssuer();
    }
}
