<?php

namespace Safepay\HttpClient;

interface ClientInterface
{
  /**
   * @param 'delete'|'get'|'post' $method The HTTP method being used
   * @param string $absUrl The URL being requested, including domain and protocol
   * @param array $headers Headers to be used in the request (full strings, not KV pairs)
   * @param array $params KV pairs for parameters. Can be nested for arrays and hashes
   *
   * @throws \Safepay\Exception\ApiConnectionException
   * @throws \Safepay\Exception\UnexpectedValueException
   *
   * @return array an array whose first element is raw request body, second
   *    element is HTTP status code and third array of HTTP headers
   */
  public function request($method, $absUrl, $headers, $params);
}
