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
 *     composer run-script server
 *
 * This command warms the cache and then runs the symfony application using
 * `dev_appserver.py`. It is the equivalent of the following two commands:
 *
 *     app/console cache:warmup --no-debug --env=dev
 *     dev_appserver.py .
 *
 * Optional arguments can be passed in, and will be added to the
 * `dev_appserver.py` command, for instance:
 *
 *     composer run-script server /path/to/my-project --project my-project
 *
 * To deploy to production, run `composer run-script deploy`.
 */

// Run the cache warmup command.
$cacheWarmupCmd = 'app/console cache:warmup --no-debug --env=dev';
passthru($cacheWarmupCmd);

// If optional args were passed into the script, append them to the
// `dev_appserver.py` command.
$optionalArgs = array_splice($argv, 1);
if (0 === count($optionalArgs)) {
    $optionalArgs[] = '.';
}
$devAppserverCmd = sprintf('dev_appserver.py %s', implode(' ', $optionalArgs));
passthru($devAppserverCmd);