<?php

/*
 * Copyright 2015 Google Inc.
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

namespace AppEngine\HelloWorldBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/storage", name="storage")
     */
    public function storageAction()
    {
        $bucketName = $this->getParameter('google_storage_bucket');
        if ($bucketName == 'YOUR_GCS_BUCKET_NAME') {
            throw new \InvalidArgumentException('Change YOUR_GCS_BUCKET_NAME '
                . 'to the name of your Cloud Storage bucket in '
                . '"app/config/parameters.yml"');
        }
        $fileUri = sprintf('gs://%s/helloworld.txt', $bucketName, $filename);
        if (!file_exists($fileUri)) {
            file_put_contents($fileUri, 'Hello World!');
        }

        // Use the Cloud Storage stream wrapper to read the file, a la
        // "gs://<bucket-name>/<object-name>"
        $this->get('logger')->info('Reading from: '. $fileUri);
        return new Response(file_get_contents($fileUri));
    }
}
