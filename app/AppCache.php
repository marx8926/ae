<?php

require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class AppCache extends HttpCache
{
      protected $whitelist = array('192.168.0.1', '127.0.0.1');

    protected function invalidate(Request $request) {
        if ('PURGE' !== $request->getMethod()) {
            return parent::invalidate($request);
        }

        // use a request matcher for smarter behavior
        if (!in_array($request->getClientIp(), $this->whitelist)) {
            return new Response('', 405);
        }

        $this->store->purge($request->getUri());

        $response = new Response();
        $response->setStatusCode(200, 'Purged');

        return $response;
    }
}
