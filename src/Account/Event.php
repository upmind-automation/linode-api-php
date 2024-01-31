<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Account;

use Linode\Entity;
use Linode\LinodeInstances\LinodeEntity;

/**
 * An Event is an action taken against an entity related to your Account.
 * For example, booting a Linode would create an Event.
 *
 * @property int          $id               The unique ID of this Event.
 * @property string       $username         The username of the User who caused the Event.
 * @property string       $action           The action that caused this Event. New actions may be added in the
 *                                          future (@see `ACTION_...` constants).
 * @property LinodeEntity $entity           Detailed information about the Event's entity, including ID, type,
 *                                          label, and URL used to access it.
 * @property string       $created          When this Event was created.
 * @property string       $status           The current status of this Event (@see `STATUS_...` constants).
 * @property bool         $seen             If this Event has been seen.
 * @property bool         $read             If this Event has been read.
 * @property string       $rate             The rate of completion of the Event. Only some Events will return
 *                                          rate; for example, migration and resize Events.
 * @property null|int     $percent_complete A percentage estimating the amount of time remaining for an Event.
 *                                          Returns `null` for notification events.
 * @property null|string  $time_remaining   The estimated time remaining until the completion of this Event.
 *                                          This value is only returned for some in-progress migration events.
 *                                          For all other in-progress events, the `percent_complete` attribute
 *                                          will indicate about how much more work is to be done.
 */
class Event extends Entity
{
    // Available fields.
    public const FIELD_ID               = 'id';
    public const FIELD_USERNAME         = 'username';
    public const FIELD_ACTION           = 'action';
    public const FIELD_CREATED          = 'created';
    public const FIELD_STATUS           = 'status';
    public const FIELD_SEEN             = 'seen';
    public const FIELD_READ             = 'read';
    public const FIELD_RATE             = 'rate';
    public const FIELD_PERCENT_COMPLETE = 'percent_complete';
    public const FIELD_TIME_REMAINING   = 'time_remaining';

    // `FIELD_ACTION` values.
    public const ACTION_ACCOUNT_UPDATE                   = 'account_update';
    public const ACTION_ACCOUNT_SETTINGS_UPDATE          = 'account_settings_update';
    public const ACTION_BACKUPS_ENABLE                   = 'backups_enable';
    public const ACTION_BACKUPS_CANCEL                   = 'backups_cancel';
    public const ACTION_BACKUPS_RESTORE                  = 'backups_restore';
    public const ACTION_COMMUNITY_QUESTION_REPLY         = 'community_question_reply';
    public const ACTION_COMMUNITY_LIKE                   = 'community_like';
    public const ACTION_CREDIT_CARD_UPDATED              = 'credit_card_updated';
    public const ACTION_DISK_CREATE                      = 'disk_create';
    public const ACTION_DISK_DELETE                      = 'disk_delete';
    public const ACTION_DISK_UPDATE                      = 'disk_update';
    public const ACTION_DISK_DUPLICATE                   = 'disk_duplicate';
    public const ACTION_DISK_IMAGIZE                     = 'disk_imagize';
    public const ACTION_DISK_RESIZE                      = 'disk_resize';
    public const ACTION_DNS_RECORD_CREATE                = 'dns_record_create';
    public const ACTION_DNS_RECORD_DELETE                = 'dns_record_delete';
    public const ACTION_DNS_RECORD_UPDATE                = 'dns_record_update';
    public const ACTION_DNS_ZONE_CREATE                  = 'dns_zone_create';
    public const ACTION_DNS_ZONE_DELETE                  = 'dns_zone_delete';
    public const ACTION_DNS_ZONE_IMPORT                  = 'dnz_zone_import';
    public const ACTION_DNS_ZONE_UPDATE                  = 'dns_zone_update';
    public const ACTION_HOST_REBOOT                      = 'host_reboot';
    public const ACTION_IMAGE_DELETE                     = 'image_delete';
    public const ACTION_IMAGE_UPDATE                     = 'image_update';
    public const ACTION_IPADDRESS_UPDATE                 = 'ipaddress_update';
    public const ACTION_LASSIE_REBOOT                    = 'lassie_reboot';
    public const ACTION_LISH_BOOT                        = 'lish_boot';
    public const ACTION_LINODE_ADDIP                     = 'linode_addip';
    public const ACTION_LINODE_BOOT                      = 'linode_boot';
    public const ACTION_LINODE_CLONE                     = 'linode_clone';
    public const ACTION_LINODE_CREATE                    = 'linode_create';
    public const ACTION_LINODE_DELETE                    = 'linode_delete';
    public const ACTION_LINODE_UPDATE                    = 'linode_update';
    public const ACTION_LINODE_DELETEIP                  = 'linode_deleteip';
    public const ACTION_LINODE_MIGRATE                   = 'linode_migrate';
    public const ACTION_LINODE_MIGRATE_DATACENTER        = 'linode_migrate_datacenter';
    public const ACTION_LINODE_MIGRATE_DATACENTER_CREATE = 'linode_migrate_datacenter_create';
    public const ACTION_LINODE_MUTATE                    = 'linode_mutate';
    public const ACTION_LINODE_MUTATE_CREATE             = 'linode_mutate_create';
    public const ACTION_LINODE_REBOOT                    = 'linode_reboot';
    public const ACTION_LINODE_REBUILD                   = 'linode_rebuild';
    public const ACTION_LINODE_RESIZE                    = 'linode_resize';
    public const ACTION_LINODE_RESIZE_CREATE             = 'linode_resize_create';
    public const ACTION_LINODE_SHUTDOWN                  = 'linode_shutdown';
    public const ACTION_LINODE_SNAPSHOT                  = 'linode_snapshot';
    public const ACTION_LINODE_CONFIG_CREATE             = 'linode_config_create';
    public const ACTION_LINODE_CONFIG_DELETE             = 'linode_config_delete';
    public const ACTION_LINODE_CONFIG_UPDATE             = 'linode_config_update';
    public const ACTION_LONGVIEWCLIENT_CREATE            = 'longviewclient_create';
    public const ACTION_LONGVIEWCLIENT_DELETE            = 'longviewclient_delete';
    public const ACTION_LONGVIEWCLIENT_UPDATE            = 'longviewclient_update';
    public const ACTION_MANAGED_DISABLED                 = 'managed_disabled';
    public const ACTION_MANAGED_ENABLED                  = 'managed_enabled';
    public const ACTION_MANAGED_SERVICE_CREATE           = 'managed_service_create';
    public const ACTION_MANAGED_SERVICE_DELETE           = 'managed_service_delete';
    public const ACTION_NODEBALANCER_CREATE              = 'nodebalancer_create';
    public const ACTION_NODEBALANCER_DELETE              = 'nodebalancer_delete';
    public const ACTION_NODEBALANCER_UPDATE              = 'nodebalancer_update';
    public const ACTION_NODEBALANCER_CONFIG_CREATE       = 'nodebalancer_config_create';
    public const ACTION_NODEBALANCER_CONFIG_DELETE       = 'nodebalancer_config_delete';
    public const ACTION_NODEBALANCER_CONFIG_UPDATE       = 'nodebalancer_config_update';
    public const ACTION_NODEBALANCER_NODE_CREATE         = 'nodebalancer_node_create';
    public const ACTION_NODEBALANCER_NODE_DELETE         = 'nodebalancer_node_delete';
    public const ACTION_NODEBALANCER_NODE_UPDATE         = 'nodebalancer_node_update';
    public const ACTION_OAUTH_CLIENT_CREATE              = 'oauth_client_create';
    public const ACTION_OAUTH_CLIENT_DELETE              = 'oauth_client_delete';
    public const ACTION_OAUTH_CLIENT_SECRET_RESET        = 'oauth_client_secret_reset';
    public const ACTION_OAUTH_CLIENT_UPDATE              = 'oauth_client_update';
    public const ACTION_PASSWORD_RESET                   = 'password_reset';
    public const ACTION_PAYMENT_SUBMITTED                = 'payment_submitted';
    public const ACTION_PROFILE_UPDATE                   = 'profile_update';
    public const ACTION_STACKSCRIPT_CREATE               = 'stackscript_create';
    public const ACTION_STACKSCRIPT_DELETE               = 'stackscript_delete';
    public const ACTION_STACKSCRIPT_UPDATE               = 'stackscript_update';
    public const ACTION_STACKSCRIPT_PUBLICIZE            = 'stackscript_publicize';
    public const ACTION_STACKSCRIPT_REVISE               = 'stackscript_revise';
    public const ACTION_TAG_CREATE                       = 'tag_create';
    public const ACTION_TAG_DELETE                       = 'tag_delete';
    public const ACTION_TFA_DISABLED                     = 'tfa_disabled';
    public const ACTION_TFA_ENABLED                      = 'tfa_enabled';
    public const ACTION_TICKET_ATTACHMENT_UPLOAD         = 'ticket_attachment_upload';
    public const ACTION_TICKET_CREATE                    = 'ticket_create';
    public const ACTION_TICKET_UPDATE                    = 'ticket_update';
    public const ACTION_TOKEN_CREATE                     = 'token_create';
    public const ACTION_TOKEN_DELETE                     = 'token_delete';
    public const ACTION_TOKEN_UPDATE                     = 'token_update';
    public const ACTION_USER_CREATE                      = 'user_create';
    public const ACTION_USER_UPDATE                      = 'user_update';
    public const ACTION_USER_DELETE                      = 'user_delete';
    public const ACTION_USER_SSH_KEY_ADD                 = 'user_ssh_key_add';
    public const ACTION_USER_SSH_KEY_DELETE              = 'user_ssh_key_delete';
    public const ACTION_USER_SSH_KEY_UPDATE              = 'user_ssh_key_update';
    public const ACTION_VLAN_ATTACH                      = 'vlan_attach';
    public const ACTION_VLAN_DETACH                      = 'vlan_detach';
    public const ACTION_VOLUME_ATTACH                    = 'volume_attach';
    public const ACTION_VOLUME_CLONE                     = 'volume_clone';
    public const ACTION_VOLUME_CREATE                    = 'volume_create';
    public const ACTION_VOLUME_DELETE                    = 'volume_delete';
    public const ACTION_VOLUME_UPDATE                    = 'volume_update';
    public const ACTION_VOLUME_DETACH                    = 'volume_detach';
    public const ACTION_VOLUME_RESIZE                    = 'volume_resize';

    // `FIELD_STATUS` values.
    public const STATUS_FAILED       = 'failed';
    public const STATUS_FINISHED     = 'finished';
    public const STATUS_NOTIFICATION = 'notification';
    public const STATUS_SCHEDULED    = 'scheduled';
    public const STATUS_STARTED      = 'started';

    /**
     * @codeCoverageIgnore This method was autogenerated.
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'entity' => new LinodeEntity($this->client, $this->data['entity']),
            default  => parent::__get($name),
        };
    }
}
