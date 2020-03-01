<?php

declare(strict_types=1);

/*
 * This file is part of the Markdown Link Linter library.
 *
 * (c) Norbert Orzechowicz <contact@norbert.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MDLinkLinter\Directory;

use RecursiveIterator;

final class DirectoryFilterIterator extends \RecursiveFilterIterator
{
    private $excludes;

    public function __construct(array $excludes, RecursiveIterator $iterator)
    {
        parent::__construct($iterator);
        $this->excludes = $excludes;
    }

    public function accept()
    {
        return !($this->isDir() && \in_array($this->getFilename(), $this->excludes, true));
    }

    public function getChildren()
    {
        return new self($this->excludes, $this->getInnerIterator()->getChildren());
    }
}
