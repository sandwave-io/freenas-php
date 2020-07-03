<?php declare(strict_types = 1);

namespace SandwaveIo\FreeNAS\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends FreeNasClientException implements NotFoundExceptionInterface
{
}
