<?php
namespace App\Services;

use Greenter\Xml\XmlSigner;
use Illuminate\Support\Facades\Storage;

class SignatureService
{
    protected $signer;

    public function __construct()
    {
        $this->signer = new XmlSigner();
        $this->signer->setCertificateFromFile(config('sunat.certificado'));
    }

    public function firmarXml($xmlPath)
    {
        $xml = Storage::get($xmlPath);
        $signedXml = $this->signer->signXml($xml);

        // Sobreescribir el archivo XML firmado
        Storage::put($xmlPath, $signedXml);
    }
}