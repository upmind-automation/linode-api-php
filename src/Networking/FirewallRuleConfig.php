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
 * @property string                $protocol    The type of network traffic affected by this rule.
 * @property null|string           $ports       A string representing the port or ports affected by this rule:
 *                                              - The string may be a single port, a range of ports, or a comma-separated list of
 *                                              single ports and port ranges. A space is permitted following each comma.
 *                                              - A range of ports is inclusive of the start and end values for the range. The end
 *                                              value of the range must be greater than the start value.
 *                                              - Ports must be within 1 and 65535, and may not contain any leading zeroes. For
 *                                              example, port "080" is not allowed.
 *                                              - The ports string can have up to 15 *pieces*, where a single port is treated as
 *                                              one piece, and a port range is treated as two pieces. For example, the string
 *                                              "22-24, 80, 443" has four pieces.
 *                                              - If no ports are configured, all ports are affected.
 *                                              - Only allowed for the TCP and UDP protocols. Ports are not allowed for the ICMP
 *                                              and IPENCAP protocols.
 * @property FirewallRuleAddresses $addresses   The IPv4 and/or IPv6 addresses affected by this rule. A Rule can have up to 255
 *                                              total addresses or networks listed across its IPv4 and IPv6 arrays. A network and
 *                                              a single IP are treated as equivalent when accounting for this limit.
 *                                              Must contain `ipv4`, `ipv6`, or both.
 * @property string                $action      Controls whether traffic is accepted or dropped by this rule. Overrides the
 *                                              Firewall's `inbound_policy` if this is an inbound rule, or the `outbound_policy`
 *                                              if this is an outbound rule.
 * @property string                $label       Used to identify this rule. For display purposes only.
 * @property string                $description Used to describe this rule. For display purposes only.
 */
class FirewallRuleConfig extends Entity
{
    // Available fields.
    public const FIELD_PROTOCOL    = 'protocol';
    public const FIELD_PORTS       = 'ports';
    public const FIELD_ADDRESSES   = 'addresses';
    public const FIELD_ACTION      = 'action';
    public const FIELD_LABEL       = 'label';
    public const FIELD_DESCRIPTION = 'description';

    // `FIELD_PROTOCOL` values.
    public const PROTOCOL_TCP     = 'TCP';
    public const PROTOCOL_UDP     = 'UDP';
    public const PROTOCOL_ICMP    = 'ICMP';
    public const PROTOCOL_IPENCAP = 'IPENCAP';

    // `FIELD_ACTION` values.
    public const ACTION_ACCEPT = 'ACCEPT';
    public const ACTION_DROP   = 'DROP';

    /**
     * @codeCoverageIgnore This method was autogenerated.
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            self::FIELD_ADDRESSES => new FirewallRuleAddresses($this->client, $this->data[$name]),
            default               => parent::__get($name),
        };
    }
}
