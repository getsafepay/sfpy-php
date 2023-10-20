<?php

namespace Safepay\Service;

/**
 * Abstract base class for all services.
 */
abstract class AbstractService
{
  /**
   * @var \Safepay\SafepayClientInterface
   */
  protected $client;

  /**
   * Initializes a new instance of the {@link AbstractService} class.
   *
   * @param \Safepay\SafepayClientInterface $client
   */
  public function __construct($client)
  {
    $this->client = $client;
  }

  /**
   * Gets the client used by this service to send requests.
   *
   * @return \Safepay\SafepayClientInterface
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * Translate null values to empty strings. For service methods,
   * we interpret null as a request to unset the field, which
   * corresponds to sending an empty string for the field to the
   * API.
   *
   * @param null|array $params
   */
  private static function formatParams($params)
  {
    if (null === $params) {
      return null;
    }
    \array_walk_recursive($params, function (&$value, $key) {
      if (null === $value) {
        $value = '';
      }
    });

    return $params;
  }

  protected function request($resource, $method, $path, $params, $opts)
  {
    return $this->getClient()->request($resource, $method, $path, self::formatParams($params), $opts);
  }

  protected function buildPath($basePath, ...$ids)
  {
    foreach ($ids as $id) {
      if (null === $id || '' === \trim($id)) {
        $msg = 'The resource ID cannot be null or whitespace.';

        throw new \Safepay\Exception\InvalidArgumentException($msg);
      }
    }

    return \sprintf($basePath, ...\array_map('\urlencode', $ids));
  }
}
