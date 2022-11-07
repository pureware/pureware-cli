<?php

namespace Pureware\PurewareCli\Generator;

use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

interface GeneratorInterface
{
    public function generate(Input $input, Output $output): int;
}
