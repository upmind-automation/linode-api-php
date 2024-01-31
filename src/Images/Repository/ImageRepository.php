<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Images\Repository;

use Linode\Entity;
use Linode\Images\Image;
use Linode\Images\ImageRepositoryInterface;
use Linode\Internal\AbstractRepository;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class ImageRepository extends AbstractRepository implements ImageRepositoryInterface
{
    public function createImage(array $parameters = []): Image
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new Image($this->client, $json);
    }

    public function updateImage(string $imageId, array $parameters = []): Image
    {
        $response = $this->client->put(sprintf('%s/%s', $this->getBaseUri(), $imageId), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new Image($this->client, $json);
    }

    public function deleteImage(string $imageId): void
    {
        $this->client->delete(sprintf('%s/%s', $this->getBaseUri(), $imageId));
    }

    protected function getBaseUri(): string
    {
        return '/images';
    }

    protected function getSupportedFields(): array
    {
        return [
            Image::FIELD_ID,
            Image::FIELD_LABEL,
            Image::FIELD_VENDOR,
            Image::FIELD_DESCRIPTION,
            Image::FIELD_IS_PUBLIC,
            Image::FIELD_SIZE,
            Image::FIELD_CREATED,
            Image::FIELD_CREATED_BY,
            Image::FIELD_DEPRECATED,
            Image::FIELD_TYPE,
            Image::FIELD_EXPIRY,
            Image::FIELD_EOL,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new Image($this->client, $json);
    }
}
