<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\LinodeInstances;

use Linode\Exception\LinodeException;
use Linode\Networking\IPAddress;

/**
 * IPAddress repository.
 */
interface IPAddressRepositoryInterface
{
    /**
     * Returns networking information for a single Linode.
     *
     * @throws LinodeException
     */
    public function getLinodeIPs(): NetworkInformation;

    /**
     * View information about the specified IP address associated with the specified
     * Linode.
     *
     * @param string $address The IP address to look up.
     *
     * @throws LinodeException
     */
    public function getLinodeIP(string $address): IPAddress;

    /**
     * Allocates a public or private IPv4 address to a Linode. Public IP Addresses, after
     * the one included with each Linode, incur an additional monthly charge. If you need
     * an additional public IP Address you must request one - please open a support
     * ticket. You may not add more than one private IPv4 address to a single Linode.
     *
     * @param array $parameters Information about the address you are creating.
     *
     * @throws LinodeException
     */
    public function addLinodeIP(array $parameters = []): IPAddress;

    /**
     * Updates a particular IP Address associated with this Linode. Only allows
     * setting/resetting reverse DNS.
     *
     * @param string $address    The IP address to look up.
     * @param array  $parameters The information to update for the IP address.
     *
     * @throws LinodeException
     */
    public function updateLinodeIP(string $address, array $parameters = []): IPAddress;

    /**
     * Deletes a public IPv4 address associated with this Linode. This will fail if it is
     * the Linode's last remaining public IPv4 address. Private IPv4 addresses cannot be
     * removed via this endpoint.
     *
     * @param string $address The IP address to look up.
     *
     * @throws LinodeException
     */
    public function removeLinodeIP(string $address): void;
}
