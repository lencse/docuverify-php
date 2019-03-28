<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use League\CommonMark\CommonMarkConverter;
use PHPHtmlParser\Dom;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use const PHP_EOL;
use function file_put_contents;
use function htmlspecialchars_decode;

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
        $this->fileSystem->remove($tmpDir);
        foreach ($config->files() as $fileName) {
            $file = new SplFileInfo(
                $config->currentDir() . '/' . $fileName,
                $config->currentDir(),
                ''
            );
            $converter = new CommonMarkConverter();
            $content = $file->getContents();
            $html = $converter->convertToHtml($content);
            $dom = new Dom();
            $dom->load($html, [
                'removeDoubleSpace' => false,
                'preserveLineBreaks' => true,
            ]);
            $codeFragments = $dom->find('code.language-php');
            $header = '<?php' . PHP_EOL;
            $relPath = $this->fileSystem->makePathRelative(
                (string) $file->getRealPath(),
                $config->currentDir()
            );
            $fileDir = $tmpDir . '/' . $relPath;
            $this->fileSystem->mkdir($fileDir);
            foreach ($codeFragments as $i => $codeFragment) {
                /** @var Dom\HtmlNode $codeFragment */
                $php = htmlspecialchars_decode($codeFragment->text());
                $phpFile = $fileDir . 'snippet' . ($i + 1) . '.php';
                file_put_contents($phpFile, $header . $php);
                if (! $this->runner->runFile($phpFile)) {
                    return false;
                }
            }
        }

        return true;
    }
}
