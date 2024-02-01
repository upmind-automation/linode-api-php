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

use Linode\Entity;

/**
 * One of a Firewall's inbound or outbound access rules. The `ports` property can be
 * used to allow traffic on a comma-separated list of different ports.
 *
 * @property string                $protocol  The type of network traffic to allow.
 * @property string                $ports     A string representing the port or ports on which traffic will be allowed:
 *                                            - The string may be a single port, a range of ports, or a comma-separated list
 *                                            of single ports and port ranges. A space is permitted following each comma.
 *                                            - A range of ports is inclusive of the start and end values for the range. The
 *                                            end value of the range must be greater than the start value.
 *                                            - Ports must be within 1 and 65535, and may not contain any leading zeroes. For
 *                                            example, port "080" is not allowed.
 *                                            - Ports may not be specified if a rule's protocol is `ICMP`. At least one port
 *                                            must be specified if a rule's protocol is `TCP` or `UDP`.
 *                                            - The ports string can have up to 15 *pieces*, where a single port is treated
 *                                            as one piece, and a port range is treated as two pieces. For example,
 *                                            the string "22-24, 80, 443" has four pieces.
 * @property FirewallRuleAddresses $addresses Allowed IPv4 or IPv6 addresses. A Rule can have up to 255 addresses or networks
 *                                            listed across its IPv4 and IPv6 arrays. A network and a single IP are treated as
 *                                            equivalent when accounting for this limit.
 */
class FirewallRuleConfig extends Entity
{
    // Available fields.
    public const FIELD_PROTOCOL  = 'protocol';
    public const FIELD_PORTS     = 'ports';
    public const FIELD_ADDRESSES = 'addresses';

    // `FIELD_PROTOCOL` values.
    public const PROTOCOL_TCP  = 'TCP';
    public const PROTOCOL_UDP  = 'UDP';
    public const PROTOCOL_ICMP = 'ICMP';
}
