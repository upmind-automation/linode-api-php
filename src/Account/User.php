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

/**
 * A User on your Account. Unrestricted users can log in and access information about
 * your Account, while restricted users may only access entities or perform actions
 * they've been granted access to.
 *
 * @property string   $username    The User's username. This is used for logging in, and may also be displayed
 *                                 alongside actions the User performs (for example, in Events or public
 *                                 StackScripts).
 * @property string   $email       The email address for the User. Linode sends emails to this address for account
 *                                 management communications. May be used for other communications as configured.
 * @property bool     $restricted  If true, the User must be granted access to perform actions or access entities on
 *                                 this Account. See User Grants View (GET /account/users/{username}/grants) for
 *                                 details on how to configure grants for a restricted User.
 * @property string[] $ssh_keys    A list of SSH Key labels added by this User.
 *                                 Users can add keys with the SSH Key Add (POST /profile/sshkeys) command.
 *                                 These keys are deployed when this User is included in the `authorized_users`
 *                                 field of the following requests:
 *                                 - Linode Create (POST /linode/instances)
 *                                 - Linode Rebuild (POST /linode/instances/{linodeId}/rebuild)
 *                                 - Disk Create (POST /linode/instances/{linodeId}/disks)
 * @property bool     $tfa_enabled A boolean value indicating if the User has Two Factor Authentication (TFA)
 *                                 enabled. See the Create Two Factor Secret (POST /profile/tfa-enable) endpoint to
 *                                 enable TFA.
 */
class User extends Entity
{
    // Available fields.
    public const FIELD_USERNAME    = 'username';
    public const FIELD_EMAIL       = 'email';
    public const FIELD_RESTRICTED  = 'restricted';
    public const FIELD_SSH_KEYS    = 'ssh_keys';
    public const FIELD_TFA_ENABLED = 'tfa_enabled';
}
