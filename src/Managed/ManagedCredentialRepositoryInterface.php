<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Managed;

use Linode\Exception\LinodeException;
use Linode\RepositoryInterface;

/**
 * Managed credential repository.
 */
interface ManagedCredentialRepositoryInterface extends RepositoryInterface
{
    /**
     * Creates a Managed Credential. A Managed Credential is stored securely
     * to allow Linode special forces to access your Managed Services and resolve
     * issues.
     *
     * @throws LinodeException
     */
    public function create(array $parameters): ManagedCredential;

    /**
     * Updates the label of a Managed Credential. This endpoint
     * does not update the username and password for a Managed Credential.
     * To do this, use the Update Managed Credential Username and Password
     * `POST /managed/credentials/{credentialId}/update` endpoint instead.
     *
     * @see self::setLoginInformation
     *
     * @throws LinodeException
     */
    public function update(int $id, array $parameters): ManagedCredential;

    /**
     * Updates the username and password for a Managed Credential.
     *
     * @throws LinodeException
     */
    public function setLoginInformation(int $id, array $parameters): void;

    /**
     * Deletes a Managed Credential. Linode special forces will no longer
     * have access to this Credential when attempting to resolve issues.
     *
     * @throws LinodeException
     */
    public function delete(int $id): void;
}
