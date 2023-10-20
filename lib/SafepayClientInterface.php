<?php

namespace Safepay;

/**
 * Interface for a Safepay client.
 */
interface SafepayClientInterface extends BaseSafepayClientInterface
{
  /**
   * Sends a request to Safepay's API.
   *
   * @param 'delete'|'get'|'post' $method the HTTP method
   * @param string $path the path of the request
   * @param array $params the parameters of the request
   * @param array|\Safepay\Util\RequestOptions $opts the special modifiers of the request
   *
   * @return \Safepay\SafepayObject the object returned by Safepay's API
   */
  public function request($resource, $method, $path, $params, $opts);
}
