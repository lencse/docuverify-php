<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use League\CommonMark\CommonMarkConverter;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Collection;
use PHPHtmlParser\Dom\HtmlNode;
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

    public function verify(Configuration $config, string $tmpDir): void
    {
        foreach ($config->files() as $fileName) {
            $this->verifyFile($config, $tmpDir, $fileName);
        }
    }

    private function verifyFile(
        Configuration $config,
        string $tmpDir,
        string $fileName
    ): void {
        $file = new SplFileInfo(
            $config->currentDir() . '/' . $fileName,
            $config->currentDir(),
            ''
        );
        if (! $file->isFile()) {
            return;
        }
        $header = '<?php' . PHP_EOL;
        $relPath = $this->fileSystem->makePathRelative(
            (string) $file->getRealPath(),
            $config->currentDir()
        );
        $fileDir = $tmpDir . '/' . $relPath;
        $this->fileSystem->mkdir($fileDir);
        foreach ($this->getCodeFragments($file) as $i => $codeFragment) {
            $this->executeFragment($codeFragment, $fileDir, (int) $i, $header);
        }
    }

    private function getCodeFragments(SplFileInfo $file): Collection
    {
        $converter = new CommonMarkConverter();
        $content = $file->getContents();
        $html = $converter->convertToHtml($content);
        $dom = new Dom();
        $dom->load($html, [
            'removeDoubleSpace' => false,
            'preserveLineBreaks' => true,
        ]);

        return $dom->find('code.language-php');
    }

    private function executeFragment(
        HtmlNode $codeFragment,
        string $fileDir,
        int $index,
        string $header
    ): void {
        /** @var Dom\HtmlNode $codeFragment */
        $php = htmlspecialchars_decode($codeFragment->text());
        $phpFile = $fileDir . 'snippet' . ($index + 1) . '.php';
        file_put_contents($phpFile, $header . $php);
        $this->runner->runFile($phpFile);
    }
}
