<?php

namespace Tests\Unit;

use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    public function test_controller_references_chat_message_model(): void
    {
        $file = file_get_contents(app_path('Http/Controllers/ChatController.php'));
        $this->assertStringContainsString('ChatMessage', $file);
        $this->assertStringContainsString('ChatMessageReaction', $file);
        $this->assertStringContainsString('ChatMessageReadReceipt', $file);
    }
}
