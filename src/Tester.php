<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use League\CommonMark\CommonMarkConverter;
use PHPHtmlParser\Dom;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use function file_put_contents;
use function htmlspecialchars_decode;
use function realpath;

final class Tester
{
    /** @var Runner */
    private $runner;

    /** @var Filesystem */
    private $fileSystem;

    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
        $this->fileSystem = new Filesystem();
    }

    public function verify(Configuration $config, string $tmpDir): bool
    {
        $this->fileSystem->mkdir($tmpDir);
        $autoladDir = realpath(__DIR__ . '/../vendor/autoload.php');
        foreach ($config->files() as $fileName) {
            $file = new SplFileInfo($config->currentDir() . '/' . $fileName, '', '');
            /** @var SplFileInfo $file */
            $converter = new CommonMarkConverter();
            $content = $file->getContents();
            $html = $converter->convertToHtml($content);
            $dom = new Dom();
            $dom->load($html, [
                'removeDoubleSpace' => false,
                'preserveLineBreaks' => true,
            ]);
            $codeFragments = $dom->find('code.language-php');
            $header = <<<EOF
<?php
namespace Readme;
require_once '$autoladDir';
EOF;
            foreach ($codeFragments as $i => $codeFragment) {
                /** @var Dom\HtmlNode $codeFragment */
                $php = htmlspecialchars_decode($codeFragment->text());
                $file = $tmpDir . '/file' . $i . '.php';
                file_put_contents($file, $header . $php);
                if (! $this->runner->runFile($file)) {
                    return false;
                }
            }
        }

        return true;
    }
}
