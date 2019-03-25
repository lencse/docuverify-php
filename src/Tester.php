<?php

namespace Lencse\Docuverify;

use League\CommonMark\CommonMarkConverter;
use PHPHtmlParser\Dom;

final class Tester
{
    public function testFile(string $path): void
    {
        $tmpdir = sys_get_temp_dir() . '/' . uniqid('', true);
        $converter = new CommonMarkConverter();
        $html = $converter->convertToHtml(file_get_contents($path));
        $dom = new Dom();
        $dom->load($html, [
            'removeDoubleSpace' => false,
            'preserveLineBreaks' => true,
        ]);
        $codeFragments = $dom->find('code.language-php');
        shell_exec('rm -rf ' . $tmpdir);
        shell_exec('mkdir ' . $tmpdir);
        $autoladDir = realpath(__DIR__ . '/../vendor/autoload.php');
        $header = <<<EOF
<?php
namespace Readme;
require_once '$autoladDir';
EOF;
        foreach ($codeFragments as $i => $codeFragment) {
            /** @var Dom\HtmlNode $codeFragment */
            $php = \htmlspecialchars_decode($codeFragment->text());
            $file = $tmpdir . '/file' . $i . '.php';
            file_put_contents($file, $header . $php);
            shell_exec('php ' . $file);
        }
    }
}
