<?php

namespace Safepay;

class Reporter extends ApiResource
{
    const OBJECT_NAME = "reporter";
    const OBJECT_PATH = "reporter.api.v2.payments";

    public function instanceUrl()
    {
        if (null === $this["token"]) {
            return "/reporter/api/v2/payments/";
        }
        return parent::instanceUrl();
    }
}
