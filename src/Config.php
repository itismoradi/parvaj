<?php

namespace MAChitgarha\Parvaj;

use MAChitgarha\Component\Pusheh;

use MAChitgarha\Phirs\DirectoryProviderFactory;

use MAChitgarha\Phirs\Util\Platform;

use Noodlehaus\Exception\FileNotFoundException;

use Noodlehaus\Parser\Json;

use Symfony\Component\Console\Exception\RuntimeException;

final class Config extends \Noodlehaus\Config
{
    private const FILE_NAME = "config.json";

    public const KEY_GHDL_VERSION = "ghdl.version";

    private const VALID_KEYS = [
        self::KEY_GHDL_VERSION,
    ];

    public string $filePath;

    public function __construct()
    {
        parent::__construct(
            $this->filePath = self::makeFile(),
            new Json()
        );
    }

    private static function makeFile(): string
    {
        $filePath = DirectoryProviderFactory::createStandard(Platform::autoDetect())->getConfigPath()
            . "/parvaj/" . self::FILE_NAME;

        if (!\is_readable($filePath)) {
            Pusheh::createDirRecursive(\dirname($filePath));
            if (!\file_put_contents($filePath, "{}")) {
                throw new RuntimeException("Cannot write to config file");
            }
        }

        return $filePath;
    }

    public function get($key, $default = null)
    {
        return parent::get($key, $default)
            ?? throw new RuntimeException("Config '$key' not set");
    }

    public static function isKeyValid(string $key): bool
    {
        return \in_array($key, self::VALID_KEYS, true);
    }

    public function getGhdlVersion(): int
    {
        return (int)($this->get(self::KEY_GHDL_VERSION));
    }

    public function setGhdlVersion(int $ghdlVersion): void
    {
        $this->set(self::KEY_GHDL_VERSION, $ghdlVersion);
    }

    public function __destruct()
    {
        parent::toFile($this->filePath);
    }
}
