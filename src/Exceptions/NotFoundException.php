<?php declare(strict_types = 1);

namespace PCextreme\FreeNAS\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends FreeNasClientException implements NotFoundExceptionInterface
{
}