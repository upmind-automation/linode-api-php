<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Networking;

use Linode\Exception\LinodeException;
use Linode\RepositoryInterface;

/**
 * Firewall repository.
 *
 * @method Firewall   find(int|string $id)
 * @method Firewall[] findAll(string $orderBy = null, string $orderDir = self::SORT_ASC)
 * @method Firewall[] findBy(array $criteria, string $orderBy = null, string $orderDir = self::SORT_ASC)
 * @method Firewall   findOneBy(array $criteria)
 * @method Firewall[] query(string $query, array $parameters = [], string $orderBy = null, string $orderDir = self::SORT_ASC)
 */
interface FirewallRepositoryInterface extends RepositoryInterface
{
    /**
     * Creates a Firewall to filter network traffic. Use the `rules` property to
     * create inbound and outbound access rules. Use the `devices` property to assign the
     * Firewall to a Linode service. Currently, Firewalls can only be assigned to Linode
     * instances.
     *
     * A Firewall can be assigned to a single Linode service at a time.
     *
     * A `firewall_create` Event is generated when this endpoint returns successfully.
     *
     * This endpoint is in **beta**.
     *
     * * Gain access to Linode Cloud Firewall by signing up for our Greenlight Beta
     * program.
     * * During the beta, Cloud Firewall is not available in every data center region.
     * For the current list of availability, see the Cloud Firewall Product
     * Documentation.
     * * Please make sure to prepend all requests with
     * `/v4beta` instead of `/v4`, and be aware that this endpoint may receive breaking
     * updates in the future. This notice will be removed when this endpoint is out of
     * beta.
     *
     * @param array $parameters Creates a Firewall object that can be applied to a Linode service to filter the
     *                          service's network traffic.
     *
     * @throws LinodeException
     */
    public function createFirewalls(array $parameters = []): Firewall;

    /**
     * Updates information for a Firewall. Some parts of a Firewall's configuration
     * cannot
     * be manipulated by this endpoint:
     *
     * - A Firewall's Devices cannot be set with this endpoint. Instead, use the
     * Create Firewall Device
     * and Delete Firewall Device
     * endpoints to assign and remove this Firewall from Linode services.
     *
     * - A Firewall's Rules cannot be changed with this endpoint. Instead, use the
     * Update Firewall Rules
     * endpoint to update your Rules.
     *
     * - A Firewall's status can be set to `enabled` or `disabled` by this endpoint, but
     * it cannot be
     * set to `deleted`. Instead, use the
     * Delete Firewall
     * endpoint to delete a Firewall.
     *
     * If a Firewall's status is changed with this endpoint, a corresponding
     * `firewall_enable` or
     * `firewall_disable` Event will be generated.
     *
     * This endpoint is in **beta**.
     *
     *
     * * Gain access to Linode Cloud Firewall by signing up for our Greenlight Beta
     * program.
     * * During the beta, Cloud Firewall is not available in every data center region.
     * For the current list of availability, see the Cloud Firewall Product
     * Documentation.
     * * Please make sure to prepend all requests with
     * `/v4beta` instead of `/v4`, and be aware that this endpoint may receive breaking
     * updates in the future. This notice will be removed when this endpoint is out of
     * beta.
     *
     * @param int   $firewallId ID of the Firewall to access.
     * @param array $parameters The Firewall information to update.
     *
     * @throws LinodeException
     */
    public function updateFirewall(int $firewallId, array $parameters = []): Firewall;

    /**
     * Delete a Firewall resource by its ID. This will remove all of the Firewall's Rules
     * from any Linode services that the Firewall was assigned to.
     *
     * A `firewall_delete` Event is generated when this endpoint returns successfully.
     *
     * This endpoint is in **beta**.
     *
     *
     * * Gain access to Linode Cloud Firewall by signing up for our Greenlight Beta
     * program.
     * * During the beta, Cloud Firewall is not available in every data center region.
     * For the current list of availability, see the Cloud Firewall Product
     * Documentation.
     * * Please make sure to prepend all requests with
     * `/v4beta` instead of `/v4`, and be aware that this endpoint may receive breaking
     * updates in the future. This notice will be removed when this endpoint is out of
     * beta.
     *
     * @param int $firewallId ID of the Firewall to access.
     *
     * @throws LinodeException
     */
    public function deleteFirewall(int $firewallId): void;
}
