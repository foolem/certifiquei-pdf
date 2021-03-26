<?php

namespace App\Services;

use App\CertificateTemplate;
use App\CertificateTemplateBuilder;
use App\S3Storage;
use App\StorageInterface;
use Exception;
use Mpdf\MpdfException;

class CertificateService {

    /**
     * @var CertificateTemplateBuilder
     * $certificateTemplateBuilder
     */
    private $certificateTemplateBuilder;

    /**
     * @var StorageInterface
     * $storage
     */
    private $storage;

    public function __construct(
        CertificateTemplateBuilder $certificateTemplateBuilder,
        S3Storage $storage
    ) {
        $this->certificateTemplateBuilder = $certificateTemplateBuilder;
        $this->storage = $storage;
    }

    /**
     * @param $payload
     * @return array
     * @throws MpdfException
     * @throws Exception
     */
    public function create($payload): array
    {
        $pages = $payload['pages'];
        $certificateTemplate = $this->buildCertificate($pages);
        $pdfUrl = $this->storeCertificateFile($certificateTemplate);

        return [
            'pdfUrl' => $pdfUrl
        ];
    }

    /**
     * @param $payload
     * @return array
     * @throws MpdfException
     * @throws Exception
     */
    public function createMany($payload): array
    {
        $certificatesData = $payload['certificatesData'];
        $certificatesURLs = [];
        foreach ($certificatesData as $certificateData) {
            $certificateTemplate = $this->buildCertificate($certificateData['pages']);
            $pdfUrl = $this->storeCertificateFile($certificateTemplate);
            $certificatesURLs[] = $pdfUrl;
        }

        return [
            "pdfUrls" => $certificatesURLs
        ];
    }

    /**
     * @param array $pages
     * @return CertificateTemplate
     * @throws MpdfException
     */
    private function buildCertificate(array $pages): CertificateTemplate
    {
        $this->certificateTemplateBuilder->buildPDFEngine(CertificateTemplateBuilder::MPDF);
        $this->certificateTemplateBuilder->buildPages($pages);

        return $this->certificateTemplateBuilder->getResult();
    }

    /**
     * @param CertificateTemplate $certificateTemplate
     * @return string
     * @throws Exception
     */
    private function storeCertificateFile($certificateTemplate): string {
        $fileName = uniqid('certificate_') . '.pdf';
        $basePath = $this->storage->getBasePath();
        $filePath = $basePath . $fileName;
        $certificateTemplate->generatePdf();
        $certificateTemplate->output($filePath);

        return $this->storage->getFileUrl($fileName);
    }
}
