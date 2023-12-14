<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SignatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_has_not_tempered_signature_and_200_response(): void
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
                            file_get_contents(public_path('sample-file/signature/testInvalidSingature.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','invalid_signature')
            ->assertStatus(200);
    }
}
