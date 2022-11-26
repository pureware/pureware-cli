<?php declare(strict_types=1);

namespace Pureware\PurewareCli\Generator\MainJs;

class MainJsImportFactory
{
   public function createImport(string $path): MainJsImport {
       return (new MainJsImport())->setImportPath($path);
   }
}
