<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Entity;

use Linode\Entity\Account\UserGrant;
use Linode\Entity\Profile\ProfileInformation;
use Linode\Entity\Profile\TwoFactorSecret;
use Linode\Exception\LinodeException;
use Linode\Internal\Profile\AuthorizedAppRepository;
use Linode\Internal\Profile\PersonalAccessTokenRepository;
use Linode\Internal\Profile\SSHKeyRepository;
use Linode\LinodeClient;
use Linode\Repository\Profile as ProfileRepository;
use Linode\Repository\RepositoryInterface;

/**
 * A Profile represents your User in our system. This is where you can change
 * information about your User. This information is available to any OAuth Client
 * regardless of requested scopes, and can be used to populate User information
 * in third-party applications.
 *
 * @property ProfileRepository\AuthorizedAppRepositoryInterface       $apps
 * @property ProfileRepository\SSHKeyRepositoryInterface              $ssh_keys
 * @property ProfileRepository\PersonalAccessTokenRepositoryInterface $tokens
 */
class Profile
{
    protected const SUCCESS_NO_CONTENT = 204;

    /**
     * Profile constructor.
     *
     * @param LinodeClient $client linode API client
     */
    public function __construct(protected LinodeClient $client) {}

    /**
     * Returns requested repository.
     *
     * @param string $name repository name
     */
    public function __get(string $name): ?RepositoryInterface
    {
        return match ($name) {
            'apps'     => new AuthorizedAppRepository($this->client),
            'ssh_keys' => new SSHKeyRepository($this->client),
            'tokens'   => new PersonalAccessTokenRepository($this->client),
            default    => null,
        };
    }

    /**
     * Returns information about the current User. This can be used to see
     * who is acting in applications where more than one token is managed. For
     * example, in third-party OAuth applications.
     *
     * This endpoint is always accessible, no matter what OAuth scopes the acting token has.
     *
     * @throws LinodeException
     */
    public function getProfileInformation(): ProfileInformation
    {
        $response = $this->client->api($this->client::REQUEST_GET, '/profile');
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ProfileInformation($this->client, $json);
    }

    /**
     * Update information in your Profile. This endpoint requires the
     * "account:read_write" OAuth Scope.
     *
     * @throws LinodeException
     */
    public function setProfileInformation(array $parameters): ProfileInformation
    {
        $response = $this->client->api($this->client::REQUEST_PUT, '/profile', $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ProfileInformation($this->client, $json);
    }

    /**
     * Disables Two Factor Authentication for your User. Once successful,
     * login attempts from untrusted computers will only require a password
     * before being successful. This is less secure, and is discouraged.
     *
     * @throws LinodeException
     */
    public function disable2FA(): void
    {
        $this->client->api($this->client::REQUEST_POST, '/profile/tfa-disable');
    }

    /**
     * Generates a Two Factor secret for your User. TFA will not be enabled until you
     * have successfully confirmed the code you were given with `confirm2FA` (see below).
     * Once enabled, logins from untrusted computers will be required to provide
     * a TFA code before they are successful.
     *
     * @return TwoFactorSecret two Factor secret generated
     *
     * @throws LinodeException
     */
    public function enable2FA(): TwoFactorSecret
    {
        $response = $this->client->api($this->client::REQUEST_POST, '/profile/tfa-enable');
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new TwoFactorSecret($this->client, $json);
    }

    /**
     * Confirms that you can successfully generate Two Factor codes and
     * enables TFA on your Account. Once this is complete, login attempts
     * from untrusted computers will be required to provide a Two Factor code
     * before they are successful.
     *
     * @param string $tfa_code The Two Factor code you generated with your Two Factor secret.
     *                         These codes are time-based, so be sure it is current.
     *
     * @return string A one-use code that can be used in place of your Two Factor
     *                code, in case you are unable to generate one. Keep this in
     *                a safe place to avoid being locked out of your Account.
     *
     * @throws LinodeException
     */
    public function confirm2FA(string $tfa_code): string
    {
        $parameters = [
            'tfa_code' => $tfa_code,
        ];

        $response = $this->client->api($this->client::REQUEST_POST, '/profile/tfa-enable-confirm', $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return $json['scratch'];
    }

    /**
     * This returns a GrantsResponse describing what the acting User has been
     * granted access to. For unrestricted users, this will return a 204 and
     * no body because unrestricted users have access to everything without
     * grants. This will not return information about entities you do not have
     * access to. This endpoint is useful when writing third-party OAuth
     * applications to see what options you should present to the acting User.
     *
     * @throws LinodeException
     */
    public function getGrants(): ?UserGrant
    {
        $response = $this->client->api($this->client::REQUEST_GET, '/profile/grants');

        if (self::SUCCESS_NO_CONTENT === $response->getStatusCode()) {
            return null;
        }

        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new UserGrant($this->client, $json);
    }
}
