<?php

namespace Safepay\Exception;

/**
 * Implements properties and methods common to all (non-SPL) Safepay exceptions.
 */
abstract class ApiErrorException extends \Exception implements ExceptionInterface
{
  protected $error;
  protected $httpBody;
  protected $httpHeaders;
  protected $httpStatus;
  protected $jsonBody;
  protected $requestId;
  protected $safepayCode;

  /**
   * Creates a new API error exception.
   *
   * @param string $message the exception message
   * @param null|int $httpStatus the HTTP status code
   * @param null|string $httpBody the HTTP body as a string
   * @param null|array $jsonBody the JSON deserialized body
   * @param null|array|\Safepay\Util\CaseInsensitiveArray $httpHeaders the HTTP headers array
   * @param null|string $safepayCode the Safepay error code
   *
   * @return static
   */
  public static function factory(
    $message,
    $httpStatus = null,
    $httpBody = null,
    $jsonBody = null,
    $httpHeaders = null
  ) {
    $instance = new static($message);
    $instance->setHttpStatus($httpStatus);
    $instance->setHttpBody($httpBody);
    $instance->setHttpHeaders($httpHeaders);

    $instance->setError($message);

    return $instance;
  }

  /**
   * Gets the Safepay error object.
   *
   * @return null|\Safepay\ErrorObject
   */
  public function getError()
  {
    return $this->error;
  }

  /**
   * Sets the Safepay error object.
   *
   * @param null|\Safepay\ErrorObject $error
   */
  public function setError($error)
  {
    $this->error = $error;
  }

  /**
   * Gets the HTTP body as a string.
   *
   * @return null|string
   */
  public function getHttpBody()
  {
    return $this->httpBody;
  }

  /**
   * Sets the HTTP body as a string.
   *
   * @param null|string $httpBody
   */
  public function setHttpBody($httpBody)
  {
    $this->httpBody = $httpBody;
  }

  /**
   * Gets the HTTP headers array.
   *
   * @return null|array|\Safepay\Util\CaseInsensitiveArray
   */
  public function getHttpHeaders()
  {
    return $this->httpHeaders;
  }

  /**
   * Sets the HTTP headers array.
   *
   * @param null|array|\Safepay\Util\CaseInsensitiveArray $httpHeaders
   */
  public function setHttpHeaders($httpHeaders)
  {
    $this->httpHeaders = $httpHeaders;
  }

  /**
   * Gets the HTTP status code.
   *
   * @return null|int
   */
  public function getHttpStatus()
  {
    return $this->httpStatus;
  }

  /**
   * Sets the HTTP status code.
   *
   * @param null|int $httpStatus
   */
  public function setHttpStatus($httpStatus)
  {
    $this->httpStatus = $httpStatus;
  }
}
