<?php

namespace MAChitgarha\Parvaj\Runner\Ghdl\VersionSpecific;

use MAChitgarha\Parvaj\Runner\Ghdl\GhdlRunner;

class GhdlRunnerV0 extends GhdlRunner
{
    protected function getElabRunGeneralOptions(string $testEntityName): array
    {
        $result = parent::getElabRunGeneralOptions($testEntityName);
        unset($result["o"]);

        return $result;
    }
}