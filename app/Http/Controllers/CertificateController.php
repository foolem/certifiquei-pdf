<?php

namespace App\Http\Controllers;

use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Mpdf\MpdfException;

class CertificateController extends Controller
{
    /**
     * @var CertificateService
     * $certificateService
     */
    private $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws MpdfException
     */
    public function create(Request $request): Response
    {
        $validationArray = $this->getCreateValidationArray();
        $this->validate($request, $validationArray);
        $certificate = $this->certificateService->create($request->all());

        return new Response($certificate, 200);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws MpdfException
     */
    public function createMany(Request $request): Response
    {
        $validationArray = $this->getCreateManyValidationArray();
        $this->validate($request, $validationArray);
        $certificates = $this->certificateService->createMany($request->all());

        return new Response($certificates, 200);
    }

    private function getCreateManyValidationArray(): array
    {
        return [
            'certificatesData' => 'array|required|max:50',
            'certificatesData.*.pages' => 'array|required|max:10',
            'certificatesData.*.pages.*.pagePlaceholdersAndValues' => 'array',
            'certificatesData.*.pages.*.pagePlaceholdersAndValues' => 'array',
            'certificatesData.*.pages.*.pageTemplate' => 'required',
            'certificatesData.*.pages.*.pageTemplate.fields' => 'array|required',
            'certificatesData.*.pages.*.pageTemplate.fields.*.value' => 'string|required_without:certificatesData.*.pages.*.pageTemplate.fields.*.img',
            'certificatesData.*.pages.*.pageTemplate.fields.*.img' => 'string|required_without:certificatesData.*.pages.*.pageTemplate.fields.*.value',
            'certificatesData.*.pages.*.pageTemplate.fields.*.position' => 'array',
            'certificatesData.*.pages.*.pageTemplate.fields.*.position.y' => 'numeric|required_with:certificatesData.*.pages.*.pageTemplate.fields.*.position',
            'certificatesData.*.pages.*.pageTemplate.fields.*.position.x' => 'numeric|required_with:certificatesData.*.pages.*.pageTemplate.fields.*.position'
        ];
    }

    /**
     * @return array
     */
    private function getCreateValidationArray(): array
    {
        return [
            'pages' => 'array|required|max:10',
            'pages.*.pagePlaceholdersAndValues' => 'array',
            'pages.*.pagePlaceholdersAndValues' => 'array',
            'pages.*.pageTemplate' => 'required',
            'pages.*.pageTemplate.fields' => 'array|required',
            'pages.*.pageTemplate.fields.*.value' => 'string|required_without:pages.*.pageTemplate.fields.*.img',
            'pages.*.pageTemplate.fields.*.img' => 'string|required_without:pages.*.pageTemplate.fields.*.value',
            'pages.*.pageTemplate.fields.*.position' => 'array',
            'pages.*.pageTemplate.fields.*.position.y' => 'numeric|required_with:pages.*.pageTemplate.fields.*.position',
            'pages.*.pageTemplate.fields.*.position.x' => 'numeric|required_with:pages.*.pageTemplate.fields.*.position'
        ];
    }
}
