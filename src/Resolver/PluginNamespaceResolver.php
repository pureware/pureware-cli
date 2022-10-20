<?php

namespace Pure\PurewareCli\Resolver;

class PluginNamespaceResolver implements NamespaceResolverInterface
{

    protected string $pluginBaseNamespace;
    protected string $pluginSrcPath;

    public function resolvePluginNamespace(string $composerJson)
    {
        $separator = '_NAMESPACE_SEPARATOR_';
        $composer = json_decode(stripslashes(str_replace('\\', $separator, $composerJson)), true); //workaround for stripslashes
        $autoload = $composer['autoload'] ?? [];

        if ($composer['type'] !== 'shopware-platform-plugin') {
            throw new \RuntimeException('Can resolve namespace only for composer type shopware-platform-plugin.');
        }

        $namespace = null;
        foreach ($autoload as $psr => $path) {
            if (strtolower($psr) !== 'psr-4') {
                continue;
            }

            $namespace = str_replace($separator, '\\', key($path));
            $namespace = str_replace('\\\\', '\\', $namespace);
            $this->pluginBaseNamespace = rtrim($namespace, '\\');
            $this->pluginSrcPath = getcwd() . DIRECTORY_SEPARATOR . rtrim(current($path), '/');
        }

        if (is_null($namespace)) {
            throw new \RuntimeException('Could not resolve plugin namespace.');
        }
    }

    public function getFullNamespace(?string $additional = null): string
    {
        if (!$additional) {
            return $this->pluginBaseNamespace;
        }

        $additional = trim($additional, '/');
        $additional = rtrim($additional, '/');
        $additional = str_replace('/', '\\', $additional);
        return $this->pluginBaseNamespace . '\\' . $additional;
    }

    public function isNamespace(string $path): bool
    {
      return true;
    }


    public function getWorkingDir(?string $additional = null): string
    {
        if (!$additional) {
            return $this->pluginSrcPath;
        }

        $additional = trim($additional, '/');
        $additional = rtrim($additional, '/');
        return $this->pluginSrcPath . DIRECTORY_SEPARATOR . $additional;
    }
}
