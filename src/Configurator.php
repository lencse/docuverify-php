<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use DOMDocument;
use DOMElement;
use DOMXPath;
use function dirname;
use Lencse\Docuverify\Exception\ConfigFileNotFound;
use function realpath;
use Symfony\Component\Filesystem\Filesystem;

final class Configurator
{
    public function fromXml(string $xmlFilePath): Configuration
    {
        if (!(new Filesystem())->exists($xmlFilePath)) {
            throw new ConfigFileNotFound($xmlFilePath);
        }
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->load($xmlFilePath);
        $dom->schemaValidate(__DIR__ . '/xml/schema.xsd');
        $xpath = new DOMXPath($dom);
        /** @var DOMElement $bootstrapNode */
        $bootstrapNode = $xpath
            ->query('//docuverify/bootstrap')
            ->item(0);
        $bootstrapPath = $bootstrapNode
            ->getAttribute('path');
        /** @var DOMElement $headerNode */
        $headerNode = $xpath
            ->query('//docuverify/header')
            ->item(0);
        $header = $headerNode
            ->textContent;
        $result = new Configuration(
            $bootstrapPath,
            $header,
            realpath(dirname($xmlFilePath)) ?: ''
        );
        $fileNodes = $xpath
            ->query('//docuverify/files/file');
        foreach ($fileNodes as $fileNode) {
            /** @var DOMElement $fileNode */
            $result = $result->withFile($fileNode->getAttribute('path'));
        }

        return $result;
    }
}
