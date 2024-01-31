<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Longview;

use Linode\Exception\LinodeException;
use Linode\RepositoryInterface;

/**
 * Longview client repository.
 */
interface LongviewClientRepositoryInterface extends RepositoryInterface
{
    /**
     * Creates a Longview Client. This Client will not begin monitoring
     * the status of your server until you configure the Longview
     * Client application on your Linode using the returning `install_code`
     * and `api_key`.
     *
     * @throws LinodeException
     */
    public function create(array $parameters): LongviewClient;

    /**
     * Updates a Longview Client. This cannot update how it monitors your
     * server; use the Longview Client application on your Linode for
     * monitoring configuration.
     *
     * @throws LinodeException
     */
    public function update(int $id, array $parameters): LongviewClient;

    /**
     * Deletes a Longview Client from your Account.
     * This does not uninstall the Longview Client application for your
     * Linode - you must do that manually.
     *
     * WARNING! All information stored for this client will be lost.
     *
     * @throws LinodeException
     */
    public function delete(int $id): void;
}
