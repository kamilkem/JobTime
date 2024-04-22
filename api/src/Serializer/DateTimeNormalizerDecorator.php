<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Serializer;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsDecorator(decorates: 'serializer.normalizer.datetime')]
final readonly class DateTimeNormalizerDecorator implements NormalizerInterface, DenormalizerInterface
{
    private const array SUPPORTED_TYPES = [
        DateTimeInterface::class => true,
        DateTimeImmutable::class => true,
        DateTime::class => true,
        CarbonInterface::class => true,
        CarbonImmutable::class => true,
        Carbon::class => true,
    ];

    public function __construct(private DateTimeNormalizer $decorated)
    {
    }

    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $this->decorated->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): \DateTimeInterface {
        $datetime = $this->decorated->denormalize($data, $type, $format, $context);

        if (CarbonInterface::class === $type || CarbonImmutable::class === $type) {
            return CarbonImmutable::instance($datetime);
        }
        if (Carbon::class === $type) {
            return Carbon::instance($datetime);
        }

        return $datetime;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return isset(self::SUPPORTED_TYPES[$type]);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            DateTimeInterface::class => true,
            DateTimeImmutable::class => true,
            DateTime::class => true,
            CarbonInterface::class => true,
            CarbonImmutable::class => true,
            Carbon::class => true,
        ];
    }
}
