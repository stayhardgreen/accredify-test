<?php

namespace App\DTO;

use App\Exceptions\RecipientException;
use App\Exceptions\ValidateIssuerException;
use App\Exceptions\ValidateSignatureException;
use App\Services\GoogleDNSAPIService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JsonFileUploadDTO
{
    private readonly array $jsonToArray;
    public function __construct(
        public readonly string $user_id,
        public readonly UploadedFile $file,
    ){}

    public function jsonConvertedData(array $jsonToArrayData): self
    {
        $this->jsonToArray =  $jsonToArrayData;
        return $this;
    }

    /**
     * @throws RecipientException
     */
    public function validateRecipient(): self
    {
        if(
            !empty($this->jsonToArray) &&
            !empty($this->jsonToArray['data']) &&
            !empty($this->jsonToArray['data']['recipient']) &&
            !empty($this->jsonToArray['data']['recipient']['name']) &&
            !empty($this->jsonToArray['data']['recipient']['email'])
        ){
            return $this;
        }

        throw new RecipientException('recipient must have name and email');
    }

    /**
     * @throws ValidateIssuerException
     */
    public function validateIssuer(): self
    {
        $this
            ->validateIssuerCase1()
            ->validateIssuerCase2();

        return $this;
    }

    /**
     * @throws ValidateIssuerException
     */
    private function validateIssuerCase1(): self
    {
        if(
            !empty($this->jsonToArray) &&
            !empty($this->jsonToArray['data']) &&
            !empty($this->jsonToArray['data']['issuer']) &&
            !empty($this->jsonToArray['data']['issuer']['name']) &&
            !empty($this->jsonToArray['data']['issuer']['identityProof'])
        ){
            return $this;
        }

        throw new ValidateIssuerException('issuer must have name and identityProof');
    }

    /**
     * @throws ValidateIssuerException
     */
    private function validateIssuerCase2(): void
    {
        if(empty($this->jsonToArray['data']['issuer']['identityProof']['location'])){
            throw new ValidateIssuerException('issuer must have location in identityProof');
        }

        $googleDNSAPIService = new GoogleDNSAPIService($this->jsonToArray['data']['issuer']['identityProof']['location']);
        $dataResponse = $googleDNSAPIService->getDNSEntry();

        if(
            empty($dataResponse) ||
            empty($dataResponse['Answer'])
        ){
            throw new ValidateIssuerException('Google DNS Answer is empty');
        }

        $this->checkGoogleDNSApiResponse($dataResponse);

    }

    /**
     * @throws ValidateIssuerException
     */
    private function checkGoogleDNSApiResponse($response): void
    {
        if(empty($this->jsonToArray['data']['issuer']['identityProof']['key'])){
            throw new ValidateIssuerException('issuer must have key in identityProof');
        }

        $flag = false;
        foreach ($response['Answer'] as $res){
            if(str_contains($res['data'],$this->jsonToArray['data']['issuer']['identityProof']['key'])){
                $flag = true;
                break;
            }
        }
        if(empty($flag))
            throw new ValidateIssuerException('identityProof key is not found');

    }


    /**
     * @throws ValidateIssuerException
     * @throws ValidateSignatureException
     */
    public function validateSignature(): void
    {
        $hashString = $this->convertArrayForHash($this->jsonToArray['data']);

        if(
            empty($this->jsonToArray['signature']) ||
            empty($this->jsonToArray['signature']['targetHash'])
        ) {
            throw new ValidateSignatureException('signature is not there.');
        }

        if($hashString !== $this->jsonToArray['signature']['targetHash']){
            throw new ValidateSignatureException('targetHash is not matched');
        }
    }

    private function buildKeyPath(array|string $array, string $path): array
    {
        $result = [];
        if(is_array($array)){
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result = array_merge($result, $this->buildKeyPath($value, "{$path}.$key"));
                } else {
                    $result["{$path}.$key"] = $value;
                }
            }
        } else {
            $result["{$path}"] = $array;
        }
        return $result;
    }

    private function convertArrayForHash(array $dataArray): string
    {
        foreach ($dataArray as $k => &$v){
            $v = $this->buildKeyPath($v, $k);
        }

        $dataRes = array();
        foreach ($dataArray as $value){
            foreach ($value as $k => $val){
                $dataRes[] = hash('SHA256','{"'.$k.'":"'.$val.'"}');
            }
        }
        sort($dataRes);

        $implodeSortedArr = '["'.implode('","',$dataRes).'"]';
        return hash('SHA256',$implodeSortedArr);
    }
}
