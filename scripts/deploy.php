#!/usr/bin/env php
<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Run this script using the following command:
 *
 *     composer run-script deploy --timeout=0
 *
 * This command warms the cache and then deploys the symfony application using
 * `gcloud app deploy`. It is the equivalent of the following three commands:
 *
 *     app/console cache:clear --no-debug --env=prod
 *     app/console cache:warmup --no-debug --env=prod
 *     gcloud app deploy -q
 *
 * Optional arguments can be passed in, and will be added to the
 * `gcloud app deploy` command, for instance:
 *
 *     composer run-script deploy -- app.yaml worker.yaml --project my-project
 *
 * To run locally, run `composer run-script server`.
 */

// Run the cache clear command.
$cacheClearCmd = 'app/console cache:clear --no-debug --env=prod';
passthru($cacheClearCmd, $returnVar);

if (0 !== $returnVar) {
    exit;
}

// Run the cache warmup command.
$cacheWarmupCmd = 'app/console cache:warmup --no-debug --env=prod';
passthru($cacheWarmupCmd, $returnVar);

if (0 !== $returnVar) {
    exit;
}

// If optional args were passed into the script, add them to the gcloud command.
$optionalArgs = array_splice($argv, 1);
$deployCmd = sprintf('gcloud app deploy -q %s', implode(' ', $optionalArgs));
passthru($deployCmd);
