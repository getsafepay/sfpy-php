<?php

namespace Safepay\Exception;

/**
 * InvalidRequestException is thrown when a request is initiated with invalid
 * parameters.
 */
class InvalidRequestException extends ApiErrorException
{
  protected $safepayParam;

  /**
   * Creates a new InvalidRequestException exception.
   *
   * @param string $message the exception message
   * @param null|int $httpStatus the HTTP status code
   * @param null|string $httpBody the HTTP body as a string
   * @param null|array $jsonBody the JSON deserialized body
   * @param null|array|\Safepay\Util\CaseInsensitiveArray $httpHeaders the HTTP headers array
   * @param null|string $safepayCode the Safepay error code
   * @param null|string $safepayParam the parameter related to the error
   *
   * @return InvalidRequestException
   */
  public static function factory(
    $message,
    $httpStatus = null,
    $httpBody = null,
    $jsonBody = null,
    $httpHeaders = null
  ) {
    $instance = parent::factory($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders);


    return $instance;
  }

  /**
   * Gets the parameter related to the error.
   *
   * @return null|string
   */
  public function getSafepayParam()
  {
    return $this->safepayParam;
  }

  /**
   * Sets the parameter related to the error.
   *
   * @param null|string $safepayParam
   */
  public function setSafepayParam($safepayParam)
  {
    $this->safepayParam = $safepayParam;
  }
}
