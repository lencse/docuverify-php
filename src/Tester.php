<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use League\CommonMark\CommonMarkConverter;
use PHPHtmlParser\Dom;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function file_get_contents;
use function file_put_contents;
use function htmlspecialchars_decode;
use function realpath;
use function shell_exec;

final class Tester
{
    /** @var Runner */
    private $runner;

    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
    }

    public function run(Configuration $config, string $tmpDir): void
    {
        $finder = new Finder();
        foreach ($config->patterns() as $pattern) {
            $files = $finder->files()->in($config->currentDir())->path($pattern);
            foreach ($files as $file) {
                /** @var SplFileInfo  $file */
                $converter = new CommonMarkConverter();
                $content = file_get_contents($file->getRealPath() ?: '') ?: '';
                $html = $converter->convertToHtml($content);
                $dom = new Dom();
                $dom->load($html, [
                    'removeDoubleSpace' => false,
                    'preserveLineBreaks' => true,
                ]);
                $codeFragments = $dom->find('code.language-php');
                shell_exec('rm -rf ' . $tmpDir);
                shell_exec('mkdir ' . $tmpDir);
                $autoladDir = realpath(__DIR__ . '/../vendor/autoload.php');
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
                    $this->runner->runFile($file);
                }
            }
        }
    }
}
