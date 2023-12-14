<?php

namespace Tests\Feature;

use App\Exceptions\NotValidJsonFoundException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileUploadApiValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_file_has_not_proper_json_and_200_response(): void
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
                            file_get_contents(public_path('sample-file/testPositiveTempered.json'))
                        )
                ],
                [
                    'Accept' => 'application/json'
                ]
            );

        $response->json();

        $expectedJson = json_encode([
            "status" => false,
            "message" => "Not Valid Json Found"
        ]);
        $this->assertJson($expectedJson);

    }

    public function test_json_file_20mb_validation_and_200_response(): void
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
                            file_get_contents(public_path('sample-file/large-file.json'))
                        )
                ],
                [
                    'Accept' => 'application/json'
                ]
            );

        $response->assertJsonValidationErrorFor('file');
    }
}
