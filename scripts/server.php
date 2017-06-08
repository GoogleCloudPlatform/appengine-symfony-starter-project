<?php

passthru('app/console cache:warmup --no-debug --env=dev');
$optionalArgs = array_splice($argv, 1);
if (0 === count($optionalArgs)) {
    $optionalArgs[] = '.';
}
passthru(sprintf('dev_appserver.py %s', implode(' ', $optionalArgs)));