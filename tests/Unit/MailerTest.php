<?php
declare(strict_types=1);

namespace Rws\Mailer\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Rws\Mailer\Mailer;
use Rws\Mailer\Sender\MockProvider;

class MailerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateInstance(): void
    {
        $sut = new Mailer(new MockProvider());
        $this->assertInstanceOf(Mailer::class, $sut);
        $this->assertTrue(true);
    }
}