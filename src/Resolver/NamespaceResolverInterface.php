<?php

namespace Pure\PurewareCli\Resolver;

interface NamespaceResolverInterface
{

    public function resolvePluginNamespace(string $composerJson);

    public function getFullNamespace(?string $additional = null): string;

    public function getWorkingDir(?string $additional = null): string;

    public function isNamespace(string $path): bool;
}
