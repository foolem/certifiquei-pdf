<?php

class CertificateTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testCreateCertificateEndpoint()
    {
        $response = $this->call('POST', '/certificate', $this->getCreateCertificatePayload());
        $jsonBody = json_decode($response->content(), true);
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('pdfUrl', $jsonBody);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getCreateCertificatePayload()
    {
        $createCertificatePayload = null;
        try {
            $createCertificatePayload = file_get_contents(__DIR__ . "/createCertificatePayload.json");
        } catch(Exception $error) {
            throw new Exception($error->getMessage());
        }
        return json_decode($createCertificatePayload, true);
    }
}
