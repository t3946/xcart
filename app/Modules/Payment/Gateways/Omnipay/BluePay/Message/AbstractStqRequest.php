<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Abstract Request
 */
abstract class AbstractStqRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://secure.bluepay.com/interfaces/stq';

    // there is no sandbox but you can send transactions with mode set to
    // "TEST" or "LIVE". calling $this->setDeveloperMode(true) sets the mode
    // to TEST.
    protected $developerEndpoint = 'https://secure.bluepay.com/interfaces/stq';

    public function setReportStart($value)
    {
        return $this->setParameter('reportStart', $value);
    }

    public function setReportEnd($value)
    {
        return $this->setParameter('reportEnd', $value);
    }

    public function getReportStart()
    {
        return $this->getParameter('reportStart');
    }

    public function getReportEnd()
    {
        return $this->getParameter('reportEnd');
    }

    public function tps()
    {
        $tpsString = $this->getSecretKey() . $this->getAccountId() . $this->getReportStart() . $this->getReportEnd();
        return md5($tpsString);
    }

    public function getData()
    {
        $data = array(
            'ACCOUNT_ID' => $this->getAccountId(),
            'REPORT_START_DATE' => $this->getReportStart(),
            'REPORT_END_DATE' => $this->getReportEnd(),
            'id' => $this->getTransactionReference(),
            'TAMPER_PROOF_SEAL' => $this->tps(),
            'MODE' => $this->getDeveloperMode() ? 'TEST' : 'LIVE',
            'EXCLUDE_ERRORS' => '0',
        );
        return $data;
    }

    public function sendData($data)
    {
        $body = $data ? http_build_query($data, '', '&') : null;
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [], $body);
        return $this->response = new LookupResponse($this, $httpResponse->getBody()->getContents());
    }

}
