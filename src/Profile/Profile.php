<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Profile;

use Linode\Account\GrantsResponse;
use Linode\Account\Login;
use Linode\Entity;
use Linode\Exception\LinodeException;
use Linode\LinodeClient;
use Linode\Profile\Repository\AuthorizedAppRepository;
use Linode\Profile\Repository\PersonalAccessTokenRepository;
use Linode\Profile\Repository\SSHKeyRepository;
use Linode\Profile\Repository\TrustedDeviceRepository;

/**
 * A Profile represents your User in our system. This is where you can change
 * information about your User. This information is available to any OAuth Client
 * regardless of requested scopes, and can be used to populate User information in
 * third-party applications.
 *
 * @property int                                    $uid                   Your unique ID in our system. This value will never change, and can safely be used
 *                                                                         to identify your User.
 * @property string                                 $username              Your username, used for logging in to our system.
 * @property string                                 $email                 Your email address. This address will be used for communication with Linode as
 *                                                                         necessary.
 * @property bool                                   $restricted            If true, your User has restrictions on what can be accessed on your Account. To
 *                                                                         get details on what entities/actions you can access/perform, see /profile/grants.
 * @property bool                                   $two_factor_auth       If true, logins from untrusted computers will require Two Factor Authentication.
 *                                                                         See /profile/tfa-enable to enable Two Factor Authentication.
 * @property string                                 $timezone              The timezone you prefer to see times in. This is not used by the API directly. It
 *                                                                         is provided for the benefit of clients such as the Linode Cloud Manager and other
 *                                                                         clients built on the API. All times returned by the API are in UTC.
 * @property bool                                   $email_notifications   If true, you will receive email notifications about account activity. If false,
 *                                                                         you may still receive business-critical communications through email.
 * @property bool                                   $ip_whitelist_enabled  If true, logins for your User will only be allowed from whitelisted IPs. This
 *                                                                         setting is currently deprecated, and cannot be enabled.
 *                                                                         If you disable this setting, you will not be able to re-enable it.
 * @property string                                 $lish_auth_method      The authentication methods that are allowed when connecting to the Linode Shell
 *                                                                         (Lish).
 *                                                                         * `keys_only` is the most secure if you intend to use Lish.
 *                                                                         * `disabled` is recommended if you do not intend to use Lish at all.
 *                                                                         * If this account's Cloud Manager authentication type is set to a Third-Party
 *                                                                         Authentication method, `password_keys` cannot be used as your Lish authentication
 *                                                                         method. To view this account's Cloud Manager `authentication_type` field, send a
 *                                                                         request to the View Profile endpoint.
 * @property null|string[]                          $authorized_keys       The list of SSH Keys authorized to use Lish for your User. This value is ignored
 *                                                                         if `lish_auth_method` is "disabled."
 * @property Referrals                              $referrals             Information about your status in our referral program.
 *                                                                         This information becomes accessible after this Profile's Account has established
 *                                                                         at least $25.00 USD of total payments.
 * @property null|string                            $verified_phone_number The phone number verified for this Profile with the **Phone Number Verify** (POST
 *                                                                         /profile/phone-number/verify) command.
 *                                                                         `null` if this Profile has no verified phone number.
 * @property string                                 $authentication_type   This account's Cloud Manager authentication type. Authentication types are chosen
 *                                                                         through
 *                                                                         Cloud Manager and authorized when logging into your account. These authentication
 *                                                                         types are either
 *                                                                         the user's password (in conjunction with their username), or the name of their
 *                                                                         indentity provider such as GitHub. For example, if a user:
 *                                                                         - Has never used Third-Party Authentication, their authentication type will be
 *                                                                         `password`.
 *                                                                         - Is using Third-Party Authentication, their authentication type will be the name
 *                                                                         of their Identity Provider (eg. `github`).
 *                                                                         - Has used Third-Party Authentication and has since revoked it, their
 *                                                                         authentication type will be `password`.
 *                                                                         **Note:** This functionality may not yet be available in Cloud Manager.
 *                                                                         See the Cloud Manager Changelog for the latest updates.
 * @property AuthorizedAppRepositoryInterface       $authorizedApps        List of OAuth apps that you've given access to your Account, and includes the
 *                                                                         level of access granted.
 * @property PersonalAccessTokenRepositoryInterface $personalAccessTokens  List of Personal Access Tokens currently active for your User.
 * @property SSHKeyRepositoryInterface              $sshKeys               List of SSH Keys you've added to your Profile.
 * @property TrustedDeviceRepositoryInterface       $trustedDevices        List of active TrustedDevices for your User. Browsers with an active Remember Me
 *                                                                         Session are logged into your account until the session expires or is revoked.
 *
 * @codeCoverageIgnore This class was autogenerated.
 */
class Profile extends Entity
{
    // Available fields.
    public const FIELD_UID                   = 'uid';
    public const FIELD_USERNAME              = 'username';
    public const FIELD_EMAIL                 = 'email';
    public const FIELD_RESTRICTED            = 'restricted';
    public const FIELD_TWO_FACTOR_AUTH       = 'two_factor_auth';
    public const FIELD_TIMEZONE              = 'timezone';
    public const FIELD_EMAIL_NOTIFICATIONS   = 'email_notifications';
    public const FIELD_IP_WHITELIST_ENABLED  = 'ip_whitelist_enabled';
    public const FIELD_LISH_AUTH_METHOD      = 'lish_auth_method';
    public const FIELD_AUTHORIZED_KEYS       = 'authorized_keys';
    public const FIELD_REFERRALS             = 'referrals';
    public const FIELD_VERIFIED_PHONE_NUMBER = 'verified_phone_number';
    public const FIELD_AUTHENTICATION_TYPE   = 'authentication_type';

    // `FIELD_LISH_AUTH_METHOD` values.
    public const LISH_AUTH_METHOD_PASSWORD_KEYS = 'password_keys';
    public const LISH_AUTH_METHOD_KEYS_ONLY     = 'keys_only';
    public const LISH_AUTH_METHOD_DISABLED      = 'disabled';

    // `FIELD_AUTHENTICATION_TYPE` values.
    public const AUTHENTICATION_TYPE_PASSWORD = 'password';
    public const AUTHENTICATION_TYPE_GITHUB   = 'github';

    /**
     * Returns information about the current User. This can be used to see who is acting
     * in applications where more than one token is managed. For example, in third-party
     * OAuth applications.
     *
     * This endpoint is always accessible, no matter what OAuth scopes the acting token
     * has.
     *
     * @throws LinodeException
     */
    public function __construct(LinodeClient $client)
    {
        parent::__construct($client);

        $response = $this->client->get('/profile');
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        $this->data = $json;
    }

    /**
     * @codeCoverageIgnore This method was autogenerated.
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            self::FIELD_REFERRALS  => new Referrals($this->client, $this->data[$name]),
            'authorizedApps'       => new AuthorizedAppRepository($this->client),
            'personalAccessTokens' => new PersonalAccessTokenRepository($this->client),
            'sshKeys'              => new SSHKeyRepository($this->client),
            'trustedDevices'       => new TrustedDeviceRepository($this->client),
            default                => parent::__get($name),
        };
    }

    /**
     * Update information in your Profile. This endpoint requires the
     * "account:read_write" OAuth Scope.
     *
     * @param array $parameters The fields to update.
     *
     * @throws LinodeException
     */
    public function updateProfile(array $parameters = []): self
    {
        $response   = $this->client->put('/profile', $parameters);
        $contents   = $response->getBody()->getContents();
        $this->data = json_decode($contents, true);

        return $this;
    }

    /**
     * This returns a GrantsResponse describing what the acting User has been granted
     * access to. For unrestricted users, this will return a  204 and no body because
     * unrestricted users have access to everything without grants. This will not return
     * information about entities you do not have access to. This endpoint is useful when
     * writing third-party OAuth applications to see what options you should present to
     * the acting User.
     *
     * For example, if they do not have `global.add_linodes`, you might not display a
     * button to deploy a new Linode.
     *
     * Any client may access this endpoint; no OAuth scopes are required.
     *
     * @throws LinodeException
     */
    public function getProfileGrants(): ?GrantsResponse
    {
        $response = $this->client->get('/profile/grants');

        if (LinodeClient::SUCCESS_NO_CONTENT === $response->getStatusCode()) {
            return null;
        }

        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new GrantsResponse($this->client, $json);
    }

    /**
     * Returns a collection of successful account logins from this user during the last
     * 90 days.
     *
     * @return Login[] An array of successful account logins from this user during the last 90 days.
     *
     * @throws LinodeException
     */
    public function getProfileLogins(): array
    {
        $response = $this->client->get('/profile/logins');
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return array_map(fn ($data) => new Login($this->client, $data), $json['data']);
    }

    /**
     * Returns a login object displaying information about a successful account login
     * from this user.
     *
     * @param int $loginId The ID of the login object to access.
     *
     * @throws LinodeException
     */
    public function getProfileLogin(int $loginId): Login
    {
        $response = $this->client->get("/profile/logins/{$loginId}");
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new Login($this->client, $json);
    }

    /**
     * View a list of user preferences tied to the OAuth client that generated
     * the token making the request. The user preferences endpoints allow
     * consumers of the API to store arbitrary JSON data, such as a user's font
     * size preference or preferred display name. User preferences are available
     * for each OAuth client registered to your account, and as such an account can
     * have multiple user preferences.
     *
     * @throws LinodeException
     */
    public function getUserPreferences(): array
    {
        $response = $this->client->get('/profile/preferences');
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    /**
     * Updates a user's preferences. These preferences are tied to the OAuth client that
     * generated the token making the request. The user preferences endpoints allow
     * consumers of the API to store arbitrary JSON data, such as a user's font size
     * preference or preferred display name. An account may have multiple preferences.
     * Preferences, and the pertaining request body, may contain any arbitrary JSON data
     * that the user would like to store.
     *
     * @param array $parameters The user preferences to update or store.
     *
     * @throws LinodeException
     */
    public function updateUserPreferences(array $parameters = []): array
    {
        $response = $this->client->put('/profile/preferences', $parameters);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    /**
     * Disables Two Factor Authentication for your User. Once successful, login attempts
     * from untrusted computers will only require a password before being successful.
     * This is less secure, and is discouraged.
     *
     * @throws LinodeException
     */
    public function tfaDisable(): void
    {
        $this->client->post('/profile/tfa-disable');
    }

    /**
     * Generates a Two Factor secret for your User. To enable TFA for your User, enter
     * the secret obtained from this command with the **Two Factor Authentication
     * Confirm/Enable** (POST /profile/tfa-enable-confirm) command.
     * Once enabled, logins from untrusted computers are required to provide
     * a TFA code before they are successful.
     *
     * **Note**: Before you can enable TFA, security questions must be answered for your
     * User by accessing the **Security Questions Answer** (POST
     * /profile/security-questions) command.
     *
     * @return TwoFactorSecret Two Factor secret generated.
     *
     * @throws LinodeException
     */
    public function tfaEnable(): TwoFactorSecret
    {
        $response = $this->client->post('/profile/tfa-enable');
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new TwoFactorSecret($this->client, $json);
    }

    /**
     * Confirms that you can successfully generate Two Factor codes and enables TFA on
     * your Account. Once this is complete, login attempts from untrusted computers will
     * be required to provide a Two Factor code before they are successful.
     *
     * @param string $tfa_code The Two Factor code you generated with your Two Factor secret. These codes are
     *                         time-based, so be sure it is current.
     *
     * @return string A one-use code that can be used in place of your Two Factor code, in case you are
     *                unable to generate one. Keep this in a safe place to avoid being locked out of
     *                your Account.
     *
     * @throws LinodeException
     */
    public function tfaConfirm(string $tfa_code): string
    {
        $parameters = [
            'tfa_code' => $tfa_code,
        ];

        $response = $this->client->post('/profile/tfa-enable-confirm', $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return $json['scratch'];
    }
}
