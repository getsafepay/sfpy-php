<?php

namespace Safepay;

/**
 * Interface for a Safepay client.
 */
interface BaseSafepayClientInterface
{
  /**
   * Gets the API key used by the client to send requests.
   *
   * @return null|string the API key used by the client to send requests
   */
  public function getApiKey();

  /**
   * Gets the base URL for Safepay's API.
   *
   * @return string the base URL for Safepay's API
   */
  public function getApiBase();
}
