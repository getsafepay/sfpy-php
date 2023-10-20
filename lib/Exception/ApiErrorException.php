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
    $instance->setJsonBody($jsonBody);
    $instance->setHttpHeaders($httpHeaders);

    $instance->setRequestId(null);
    if ($httpHeaders && isset($httpHeaders['Request-Id'])) {
      $instance->setRequestId($httpHeaders['Request-Id']);
    }

    $instance->setError($instance->constructErrorObject());

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

  /**
   * Gets the JSON deserialized body.
   *
   * @return null|array<string, mixed>
   */
  public function getJsonBody()
  {
    return $this->jsonBody;
  }

  /**
   * Sets the JSON deserialized body.
   *
   * @param null|array<string, mixed> $jsonBody
   */
  public function setJsonBody($jsonBody)
  {
    $this->jsonBody = $jsonBody;
  }

  /**
   * Gets the Safepay request ID.
   *
   * @return null|string
   */
  public function getRequestId()
  {
    return $this->requestId;
  }

  /**
   * Sets the Safepay request ID.
   *
   * @param null|string $requestId
   */
  public function setRequestId($requestId)
  {
    $this->requestId = $requestId;
  }

  /**
   * Gets the Safepay error code.
   *
   * Cf. the `CODE_*` constants on {@see \Safepay\ErrorObject} for possible
   * values.
   *
   * @return null|string
   */
  public function getSafepayCode()
  {
    return $this->safepayCode;
  }

  /**
   * Sets the Safepay error code.
   *
   * @param null|string $safepayCode
   */
  public function setSafepayCode($safepayCode)
  {
    $this->safepayCode = $safepayCode;
  }

  /**
   * Returns the string representation of the exception.
   *
   * @return string
   */
  public function __toString()
  {
    $parentStr = parent::__toString();
    $statusStr = (null === $this->getHttpStatus()) ? '' : "(Status {$this->getHttpStatus()}) ";
    $idStr = (null === $this->getRequestId()) ? '' : "(Request {$this->getRequestId()}) ";

    return "Error sending request to Safepay: {$statusStr}{$idStr}{$this->getMessage()}\n{$parentStr}";
  }

  protected function constructErrorObject()
  {
    if (null === $this->jsonBody || !\array_key_exists('error', $this->jsonBody)) {
      return null;
    }

    return \Safepay\ErrorObject::constructFrom($this->jsonBody['error']);
  }
}
