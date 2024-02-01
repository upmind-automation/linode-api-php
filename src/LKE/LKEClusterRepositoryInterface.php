<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\LKE;

use Linode\Exception\LinodeException;
use Linode\RepositoryInterface;

/**
 * LKECluster repository.
 *
 * @method LKECluster   find(int|string $id)
 * @method LKECluster[] findAll(string $orderBy = null, string $orderDir = self::SORT_ASC)
 * @method LKECluster[] findBy(array $criteria, string $orderBy = null, string $orderDir = self::SORT_ASC)
 * @method LKECluster   findOneBy(array $criteria)
 * @method LKECluster[] query(string $query, array $parameters = [], string $orderBy = null, string $orderDir = self::SORT_ASC)
 */
interface LKEClusterRepositoryInterface extends RepositoryInterface
{
    /**
     * Creates a Kubernetes cluster. The Kubernetes cluster will be created
     * asynchronously. You can use the events system to determine when the Kubernetes
     * cluster is ready to use.
     *
     * **Beta**: This endpoint is in private beta. Please make sure to prepend all
     * requests with `/v4beta` instead of `/v4`, and be aware that this endpoint may
     * receive breaking updates in the future. This notice will be removed when this
     * endpoint is out of beta. Sign up for the beta here.
     *
     * @param array $parameters Configuration for the Kubernetes cluster
     *
     * @throws LinodeException
     */
    public function createLKECluster(array $parameters = []): LKECluster;

    /**
     * Updates a Kubernetes cluster.
     *
     * **Beta**: This endpoint is in private beta. Please make sure to prepend all
     * requests with `/v4beta` instead of `/v4`, and be aware that this endpoint may
     * receive breaking updates in the future. This notice will be removed when this
     * endpoint is out of beta. Sign up for the beta here.
     *
     * @param int   $clusterId  ID of the Kubernetes cluster to look up.
     * @param array $parameters The fields to update the Kubernetes cluster.
     *
     * @throws LinodeException
     */
    public function putLKECluster(int $clusterId, array $parameters = []): LKECluster;

    /**
     * Deletes a Cluster you have permission to `read_write`.
     *
     * **Deleting a Cluster is a destructive action and cannot be undone.**
     *
     * Deleting a Cluster:
     *   - Deletes all Linodes in all pools within this Kubernetes cluster
     *   - Deletes all supporting Kubernetes services for this Kubernetes
     *     cluster (API server, etcd, etc)
     *   - Deletes all NodeBalancers created by this Kubernetes cluster
     *   - Does not delete any of the volumes created by this Kubernetes
     *     cluster
     *
     *
     * **Beta**: This endpoint is in private beta. Please make sure to prepend all
     * requests with
     * `/v4beta` instead of `/v4`, and be aware that this endpoint may receive breaking
     * updates in the future. This notice will be removed when this endpoint is out of
     * beta. Sign up for the beta here.
     *
     * @param int $clusterId ID of the Kubernetes cluster to look up.
     *
     * @throws LinodeException
     */
    public function deleteLKECluster(int $clusterId): void;
}
