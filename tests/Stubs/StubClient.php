<?php

namespace App\Tests\Stubs;
use \SplQueue;
class StubClient
{
    /** @var SplQueue<ResponseInterface> */
    private SplQueue $responses;

    public function __construct()
    {
        $this->responses = new SplQueue();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->responses->dequeue();
    }

    public function enqueueResponse(ResponseInterface $response): void
    {
        $this->responses->enqueue($response);
    }

    public function flushResponses(): void
    {
        $this->responses = new SplQueue();
    }

}