<?php

namespace Tests\Feature\User\FileConversion;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationTest extends TestCase
{

    public function test_only_auth_user_can_upload_a_file_to_convert()
    {
        $response = $this->postJson('');

        $response->assertStatus(200);
    }
}
