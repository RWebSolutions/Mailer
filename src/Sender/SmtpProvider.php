<?php
declare(strict_types=1);

namespace Rws\Mailer\Sender;

use RWS\Mailer\Message\Message;
use RWS\Mailer\Provider;

/**
 * @package RWS\Mailer\Sender
 */
class SmtpProvider implements Provider
{
    private const EOL = "\r\n";

    /**
     * @var resource
     */
    private $connection;
    /**
     * @var string
     */
    private $userName;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $host;

    /**
     * @param string $userName
     * @param string $password
     * @param string $host
     * @param int $port
     * @param bool $tls
     * @param int $timeout
     * @throws \Exception
     */
    public function __construct(string $userName, string $password, string $host, int $port = 587, bool $tls = true, ?int $timeout = 5)
    {
        $this->createConnection($host, $port ?? 587, $timeout);
        $this->sendCommand(SmtpCommand::hello(gethostname()));
        if ($tls) {
            $this->startTLS();
            $this->sendCommand(SmtpCommand::hello(gethostname()));
        }
        $this->userName = $userName;
        $this->password = $password;
        $this->host = $host;
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     */
    private function createConnection(string $host, int $port, int $timeout): void
    {
        $this->connection = \fsockopen($host, $port, $errorNo, $errorMessage, $timeout);
        if (!$this->connection) {
            throw new \RuntimeException($errorMessage, $errorNo);
        }

        $this->log($this->getResponse()->getMessage());
    }

    private function startTLS(): void
    {
        $this->sendCommand(SmtpCommand::startTls());
        \stream_socket_enable_crypto(
            $this->connection,
            true,
            STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_SSLv23_CLIENT
        );
    }

    /**
     * @param string $userName
     * @param string $password
     * @throws \Exception
     */
    private function authenticate(string $userName, string $password): void
    {
        $this->sendCommand(SmtpCommand::authenticate());
        $this->sendCommand(SmtpCommand::credentials($userName));
        $this->sendCommand(SmtpCommand::credentials($password));
    }

    public function send(Message $message): void
    {
        try {
            $this->authenticate($this->userName, $this->password);
            $this->sendCommand(SmtpCommand::mailFrom((string)$message->getSender()));
            $this->sendCommand(SmtpCommand::mailTo((string)$message->getRecipient()));
            foreach ($message->getCc() as $cc) {
                $this->sendCommand(SmtpCommand::mailTo((string)$cc));
            }
            foreach ($message->getBcc() as $bcc) {
                $this->sendCommand(SmtpCommand::mailTo((string)$bcc));
            }
            $this->sendCommand(SmtpCommand::data());
            $this->sendCommand(SmtpCommand::data($this->prepareData($message)));
            $this->sendCommand(SmtpCommand::reset());
        } catch (\Exception $e) {
            $this->log($e->getMessage(), '>');
        }
    }

    /**
     * @param SmtpCommand $command
     */
    private function sendCommand(SmtpCommand $command): void
    {
        $command = (string)$command;

        $this->logCommand($command);
        \fputs($this->connection, $command . static::EOL);

        $response = $this->getResponse();
        if ($response->getStatusCode() >= 500) {
            throw new \RuntimeException($response->getMessage());
        }

        $this->logResponse($response);
    }

    private function logCommand(string $command): void
    {
        $this->log($command, '<');
    }

    /**
     * @param string $text
     * @param string $prefix
     */
    private function log(string $text, string $prefix = '-'): void
    {
        echo $prefix . " " . str_replace(PHP_EOL, PHP_EOL . str_repeat(' ', strlen($prefix) + 1), $text) . PHP_EOL;
    }

    /**
     * @param SmtpResponse $response
     */
    private function logResponse(SmtpResponse $response): void
    {
        $this->log($response->getStatusCode() . ' ' . $response->getMessage(), '>');
    }

    /**
     * @return SmtpResponse
     */
    private function getResponse(): SmtpResponse
    {
        $response = '';
        while (($line = fgets($this->connection, 515)) !== false) {
            $response .= trim($line) . PHP_EOL;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }

        return SmtpResponse::fromRaw(trim($response));
    }

    public function __destruct()
    {
        $this->sendCommand(SmtpCommand::quit());
        fclose($this->connection);
    }

    private function prepareData(Message $message): string
    {
        $boundary = md5(uniqid((string)microtime(true), true));
        $recipient = $message->getRecipient();
        $sender = $message->getSender();
        $replyTo = $message->getReplyTo();
        $cc = $message->getCc();

        $data = [];
        $data[] = 'Date: ' . (new \DateTime())->format(\DateTime::RFC2822);
        $data[] = 'Subject: ' . (string)$message->getSubject();
        $data[] = 'From: ' . $sender->asLabel();
        $data[] = 'To: ' . $recipient->asLabel();

        if (!empty($cc)) {
            $recipients = [];
            foreach ($cc as $recipient) {
                $recipients[] = $recipient->asLabel();
            }

            $data[] = 'Cc: ' . implode(', ', $recipients);
        }

        if (null !== $replyTo) {
            $data[] = 'Reply-To: ' . $replyTo->asLabel();
        }

        $data[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        $data[] = 'Message-ID: <' . $boundary . '@' . $this->host . '>';
        $data[] = 'MIME-Version: 1.0';
        $data[] = '';
        $data[] = '--' . $boundary;
        $data[] = 'Content-Type: text/plain; charset=UTF-8';
        $data[] = 'Content-Transfer-Encoding: base64';
        $data[] = '';
        $data[] = chunk_split(base64_encode($message->getPlainTextContent() ?? ''));
        $data[] = '--' . $boundary;
        $data[] = 'Content-Type: text/html; charset=UTF-8';
        $data[] = 'Content-Transfer-Encoding: base64';
        $data[] = '';
        $data[] = chunk_split(base64_encode($message->getHtmlContent() ?? ''));
        $data[] = '--' . $boundary . '--';
        $data[] = '.';

        return implode(static::EOL, $data);
    }
}