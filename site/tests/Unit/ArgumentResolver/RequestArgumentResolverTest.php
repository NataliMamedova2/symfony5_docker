<?php

namespace App\Tests\Unit\ArgumentResolver;

use App\ArgumentResolver\RequestArgumentResolver;
use App\Exception\ValidationException;
use App\Serializer\SerializerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RequestArgumentResolverTest extends TestCase
{
    private DenormalizerInterface $denormalizer;

    protected function setUp(): void
    {
        $this->denormalizer = (new SerializerFactory())->create();
    }

    public function testResolveBadBody(): void
    {
        $request = new Request([], [], [], [], [], [], '{');
        $request->headers->set('Content-Type', 'application/json;charset=utf-8');
        $metadata = new ArgumentMetadata('request', TestRequest::class, false, false, null);
        $resolver = new RequestArgumentResolver((new ValidatorBuilder())->getValidator(), $this->denormalizer);
        static::expectException(ValidationException::class);
        $resolver->resolve($request, $metadata)->current();
    }

    public function testResolve(): void
    {
        $email = 'test45@test.test';
        $username = 'test45';
        $password = '123456789414';

        $request = new Request([], [], [], [], [], [], json_encode([
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ], JSON_THROW_ON_ERROR));
        $request->headers->set('Content-Type', 'application/json;charset=utf-8');

        $metadata = new ArgumentMetadata('request', TestRequest::class, false, false, null);
        $resolver = new RequestArgumentResolver((new ValidatorBuilder())->getValidator(), $this->denormalizer);

        $args = $resolver->resolve($request, $metadata);
        /** @var TestRequest $resolvedRequest */
        $resolvedRequest = $args->current();

        static::assertSame($email, $resolvedRequest->getEmail());
        static::assertSame($username, $resolvedRequest->getUsername());
        static::assertSame($password, $resolvedRequest->getPassword());

        $args->next();
    }
}
