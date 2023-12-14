<?php

namespace App\Http\Resources\Verification;

use App\Models\VerificationModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'issuer' => $this->{VerificationModel::FIELD_ISSUER_NAME},
            'result' => $this->{VerificationModel::FIELD_VERIFICATION_RESULT},
        ];
    }
}
