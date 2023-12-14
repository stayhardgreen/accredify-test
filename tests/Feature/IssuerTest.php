<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;


class IssuerTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_has_not_issuer_and_200_response()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(
                route('api.v1.file-upload'),
                [
                    'file' => UploadedFile::fake()
                        ->createWithContent(
                            'test1.json',
                            file_get_contents(public_path('sample-file/issuer/testInvalidIssuer.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_issuer')
            ->assertStatus(200);
    }

    public function test_issuer_has_not_name_and_200_response()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(
                route('api.v1.file-upload'),
                [
                    'file' => UploadedFile::fake()
                        ->createWithContent(
                            'test1.json',
                            file_get_contents(public_path('sample-file/issuer/testInvalidIssuerName.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_issuer')
            ->assertStatus(200);
    }

    public function test_issuer_has_not_identity_proof_and_200_response()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(
                route('api.v1.file-upload'),
                [
                    'file' => UploadedFile::fake()
                        ->createWithContent(
                            'test1.json',
                            file_get_contents(public_path('sample-file/issuer/testInvalidIssuerIdentityProof.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_issuer')
            ->assertStatus(200);
    }

    public function test_issuer_identity_proof_not_found_with_key_for_dns_and_200_response()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(
                route('api.v1.file-upload'),
                [
                    'file' => UploadedFile::fake()
                        ->createWithContent(
                            'test1.json',
                            file_get_contents(public_path('sample-file/issuer/testInvalidIssuerKeyDNS.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_issuer')
            ->assertStatus(200);
    }
}
