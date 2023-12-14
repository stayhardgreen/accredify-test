<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_has_not_recipient_and_200_response(): void
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
                            file_get_contents(public_path('sample-file/recipient-test/testInvalidRecipient.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_recipient')
            ->assertStatus(200);
    }

    public function test_recipient_has_not_email_and_200_response()
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
                            file_get_contents(public_path('sample-file/recipient-test/testInvalidRecipientEmail.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_recipient')
            ->assertStatus(200);
    }

    public function test_recipient_has_not_name_and_200_response()
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
                            file_get_contents(public_path('sample-file/recipient-test/testInvalidRecipientName.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_recipient')
            ->assertStatus(200);
    }

    /*public function test_json_has_not_valid_invalid_issuer()
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
                            file_get_contents(public_path('sample-file/testInvalidIssuer.json'))
                        )
                ]
            );

        $response->assertJsonPath('data.result','invalid_issuer');
    }*/
}
