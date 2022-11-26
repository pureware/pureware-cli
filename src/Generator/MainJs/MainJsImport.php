<?php

declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\MainJs;

class MainJsImport implements ImportInterface
{
    private string $importPath;

    public function getTemplate(): string
    {
        return sprintf(
            "import './%s';",
            $this->importPath
        );
    }

    public function getIdentifier(): string
    {
        return $this->importPath;
    }

    public function getImportPath(): string
    {
        return $this->importPath;
    }

    public function setImportPath(string $importPath): self
    {
        $this->importPath = $importPath;
        return $this;
    }
}
