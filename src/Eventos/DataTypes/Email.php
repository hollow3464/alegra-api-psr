<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

final class Email
{
    public readonly string $cc;
    public readonly string $bcc;

    /**
     * @param array<string> $cc
     * @param array<string> $bcc
     */
    public function __construct(
        public readonly string $to,
        public readonly string $replyTo,
        array $cc = [],
        array $bcc = [],
    ) {
        $this->cc = join(',', $cc);
        $this->bcc = join(',', $bcc);
    }
}
