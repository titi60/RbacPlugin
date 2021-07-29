<?php

declare(strict_types=1);

namespace spec\Titi60\SyliusRbacPlugin\Access\Model;

use PhpSpec\ObjectBehavior;
use Titi60\SyliusRbacPlugin\Access\Model\OperationType;
use Titi60\SyliusRbacPlugin\Access\Model\Section;

final class AccessRequestSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(Section::customers(), OperationType::read());
    }

    function it_has_permission(): void
    {
        $this->section()->shouldBeLike(Section::customers());
    }

    function it_has_operation_type(): void
    {
        $this->operationType()->shouldBeLike(OperationType::read());
    }
}
