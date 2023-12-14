<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_signature_and_200_response(): void
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
                            file_get_contents(public_path('sample-file/testPositive.json'))
                        )
                ]
            );

        $response
            ->assertJsonPath('data.result','verified')
            ->assertStatus(200);
    }
}
