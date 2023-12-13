<?php

namespace App\Services;

use App\Exceptions\ValidateIssuerException;
use Illuminate\Support\Facades\Http;

class GoogleDNSAPIService
{
    private readonly string $googleDNSLink;
    public function __construct(string $name)
    {
        $this->googleDNSLink = 'https://dns.google/resolve?type=TXT'.'&name='.$name;
    }

    /**
     * @throws ValidateIssuerException
     */
    public function getDNSEntry()
    {
        $responseDNSEntry = Http::get($this->googleDNSLink);
        if($responseDNSEntry->successful()){
            return $responseDNSEntry->json();
        }

        throw new ValidateIssuerException('DNS response is not available');
    }
}
