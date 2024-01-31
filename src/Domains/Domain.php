<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Domains;

use Linode\Domains\Repository\DomainRecordRepository;
use Linode\Entity;

/**
 * A domain zonefile in our DNS system. You must own the domain name and tell your
 * registrar to use Linode's nameservers in order for a domain in our system to be
 * treated as authoritative.
 *
 * @property int                             $id          This Domain's unique ID
 * @property string                          $domain      The domain this Domain represents. Domain labels cannot be longer than 63
 *                                                        characters and must conform to RFC1035. Domains must be unique on Linode's
 *                                                        platform, including across different Linode accounts; there cannot be two Domains
 *                                                        representing the same domain.
 * @property string                          $type        If this Domain represents the authoritative source of information for the domain
 *                                                        it describes, or if it is a read-only copy of a master (also called a slave).
 * @property string                          $status      Used to control whether this Domain is currently being rendered.
 * @property string                          $soa_email   Start of Authority email address. This is required for master Domains.
 * @property string                          $group       The group this Domain belongs to. This is for display purposes only.
 * @property string                          $description A description for this Domain. This is for display purposes only.
 * @property int                             $ttl_sec     "Time to Live" - the amount of time in seconds that this Domain's records may be
 *                                                        cached by resolvers or other domain servers.
 *                                                        * Valid values are 0, 300, 3600, 7200, 14400, 28800, 57600, 86400, 172800, 345600,
 *                                                        604800, 1209600, and 2419200 - any other value will be rounded to the nearest
 *                                                        valid value.
 *                                                        * ttl_sec will default to 0 if no value is provided.
 *                                                        * A value of 0 is equivalent to a value of 86400.
 * @property int                             $refresh_sec The amount of time in seconds before this Domain should be refreshed. Valid values
 *                                                        are 300, 3600, 7200, 14400, 28800, 57600, 86400, 172800, 345600, 604800, 1209600,
 *                                                        and 2419200 - any other value will be rounded to the nearest valid value.
 * @property int                             $retry_sec   The interval, in seconds, at which a failed refresh should be retried. Valid
 *                                                        values are 300, 3600, 7200, 14400, 28800, 57600, 86400, 172800, 345600, 604800,
 *                                                        1209600, and 2419200 - any other value will be rounded to the nearest valid value.
 * @property int                             $expire_sec  The amount of time in seconds that may pass before this Domain is no longer
 *                                                        authoritative. Valid values are 300, 3600, 7200, 14400, 28800, 57600, 86400,
 *                                                        172800, 345600, 604800, 1209600, and 2419200 - any other value will be rounded to
 *                                                        the nearest valid value.
 * @property string[]                        $master_ips  The IP addresses representing the master DNS for this Domain.
 * @property string[]                        $axfr_ips    The list of IPs that may perform a zone transfer for this Domain. This is
 *                                                        potentially dangerous, and should be set to an empty list unless you intend to use
 *                                                        it.
 * @property string[]                        $tags        An array of tags applied to this object. Tags are for organizational purposes
 *                                                        only.
 * @property DomainRecordRepositoryInterface $records     Domain records.
 */
class Domain extends Entity
{
    // Available fields.
    public const FIELD_ID          = 'id';
    public const FIELD_DOMAIN      = 'domain';
    public const FIELD_TYPE        = 'type';
    public const FIELD_STATUS      = 'status';
    public const FIELD_SOA_EMAIL   = 'soa_email';
    public const FIELD_GROUP       = 'group';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_TTL_SEC     = 'ttl_sec';
    public const FIELD_REFRESH_SEC = 'refresh_sec';
    public const FIELD_RETRY_SEC   = 'retry_sec';
    public const FIELD_EXPIRE_SEC  = 'expire_sec';
    public const FIELD_MASTER_IPS  = 'master_ips';
    public const FIELD_AXFR_IPS    = 'axfr_ips';
    public const FIELD_TAGS        = 'tags';

    // `FIELD_TYPE` values.
    public const TYPE_MASTER = 'master';
    public const TYPE_SLAVE  = 'slave';

    // `FIELD_STATUS` values.
    public const STATUS_DISABLED   = 'disabled';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_EDIT_MODE  = 'edit_mode';
    public const STATUS_HAS_ERRORS = 'has_errors';

    // Time values.
    public const TIME_DEFAULT  = 0;
    public const TIME_5_MINS   = 300;
    public const TIME_1_HOUR   = 3600;
    public const TIME_2_HOURS  = 7200;
    public const TIME_4_HOURS  = 14400;
    public const TIME_8_HOURS  = 28800;
    public const TIME_16_HOURS = 57600;
    public const TIME_1_DAY    = 86400;
    public const TIME_2_DAYS   = 172800;
    public const TIME_4_DAYS   = 345600;
    public const TIME_1_WEEK   = 604800;
    public const TIME_2_WEEKS  = 1209600;
    public const TIME_4_WEEKS  = 2419200;

    /**
     * @codeCoverageIgnore This method was autogenerated.
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'records' => new DomainRecordRepository($this->client, $this->id),
            default   => parent::__get($name),
        };
    }
}
