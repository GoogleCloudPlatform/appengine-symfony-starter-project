<?php

# Copyright 2017 Google Inc. All Rights Reserved.

passthru('app/console cache:warmup --no-debug --env=prod');
$optionalArgs = array_splice($argv, 1);
passthru(sprintf('gcloud app deploy -q %s', implode(' ', $optionalArgs)));
