<?php

namespace Pureware\PurewareCli\Resolver;

interface NamespaceResolverInterface
{
    public function resolvePluginNamespace(string $composerJson): void;

    public function getFullNamespace(?string $additional = null): string;

    public function getWorkingDir(?string $additional = null): string;

    public function getPluginName(): string;

    public function isNamespace(string $path): bool;
}
