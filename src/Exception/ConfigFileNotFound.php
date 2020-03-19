<?php declare(strict_types=1);

namespace Lencse\Docuverify\Exception;

use RuntimeException;

final class ConfigFileNotFound extends RuntimeException
{
    public function __construct(string $configFilePath)
    {
        parent::__construct(sprintf('Not found config file: %s', $configFilePath));
    }
}
