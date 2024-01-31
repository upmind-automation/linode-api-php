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

use GuzzleHttp\Psr7\Response;
use Linode\Account\GlobalGrant;
use Linode\Account\UserGrant;
use Linode\LinodeClient;
use Linode\Profile\Profile;
use Linode\Profile\ProfileInformation;
use Linode\Profile\ProfileReferrals;
use Linode\Profile\TwoFactorSecret;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Linode\Profile\Profile
 */
final class ProfileTest extends TestCase
{
    protected LinodeClient $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(LinodeClient::class);
    }

    public function testGetProfileInformation(): void
    {
        $response = <<<'JSON'
                        {
                            "uid": 1234,
                            "username": "exampleUser",
                            "email": "example-user@gmail.com",
                            "timezone": "US/Eastern",
                            "email_notifications": true,
                            "referrals": {
                                "code": "871be32f49c1411b14f29f618aaf0c14637fb8d3",
                                "url": "https://www.linode.com/?r=871be32f49c1411b14f29f618aaf0c14637fb8d3",
                                "total": 0,
                                "completed": 0,
                                "pending": 0,
                                "credit": 0
                            },
                            "ip_whitelist_enabled": false,
                            "lish_auth_method": "keys_only",
                            "authorized_keys": null,
                            "two_factor_auth": true,
                            "restricted": false
                        }
            JSON;

        $this->client
            ->method('get')
            ->willReturnMap([
                ['/profile', [], [], new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $entity = $profile->getProfileInformation();

        self::assertInstanceOf(ProfileInformation::class, $entity);
        self::assertSame('US/Eastern', $entity->timezone);
        self::assertInstanceOf(ProfileReferrals::class, $entity->referrals);
        self::assertSame('871be32f49c1411b14f29f618aaf0c14637fb8d3', $entity->referrals->code);
    }

    public function testSetProfileInformation(): void
    {
        $request = [
            'email'                => 'example-user@gmail.com',
            'timezone'             => 'US/Eastern',
            'email_notifications'  => true,
            'ip_whitelist_enabled' => false,
            'lish_auth_method'     => 'keys_only',
            'authorized_keys'      => null,
            'two_factor_auth'      => true,
            'restricted'           => false,
        ];

        $response = <<<'JSON'
                        {
                            "uid": 1234,
                            "username": "exampleUser",
                            "email": "example-user@gmail.com",
                            "timezone": "US/Eastern",
                            "email_notifications": true,
                            "referrals": {
                                "code": "871be32f49c1411b14f29f618aaf0c14637fb8d3",
                                "url": "https://www.linode.com/?r=871be32f49c1411b14f29f618aaf0c14637fb8d3",
                                "total": 0,
                                "completed": 0,
                                "pending": 0,
                                "credit": 0
                            },
                            "ip_whitelist_enabled": false,
                            "lish_auth_method": "keys_only",
                            "authorized_keys": null,
                            "two_factor_auth": true,
                            "restricted": false
                        }
            JSON;

        $this->client
            ->method('put')
            ->willReturnMap([
                ['/profile', $request, new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $entity = $profile->setProfileInformation([
            ProfileInformation::FIELD_EMAIL                => 'example-user@gmail.com',
            ProfileInformation::FIELD_TIMEZONE             => 'US/Eastern',
            ProfileInformation::FIELD_EMAIL_NOTIFICATIONS  => true,
            ProfileInformation::FIELD_IP_WHITELIST_ENABLED => false,
            ProfileInformation::FIELD_LISH_AUTH_METHOD     => ProfileInformation::LISH_AUTH_METHOD_KEYS_ONLY,
            ProfileInformation::FIELD_AUTHORIZED_KEYS      => null,
            ProfileInformation::FIELD_TWO_FACTOR_AUTH      => true,
            ProfileInformation::FIELD_RESTRICTED           => false,
        ]);

        self::assertInstanceOf(ProfileInformation::class, $entity);
        self::assertSame('US/Eastern', $entity->timezone);
        self::assertInstanceOf(ProfileReferrals::class, $entity->referrals);
        self::assertSame('871be32f49c1411b14f29f618aaf0c14637fb8d3', $entity->referrals->code);
    }

    public function testGetProfilePreferences(): void
    {
        $response = <<<'JSON'
                        {
                            "key1": "value1",
                            "key2": "value2"
                        }
            JSON;

        $this->client
            ->method('get')
            ->willReturnMap([
                ['/profile/preferences', [], [], new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $result = $profile->getProfilePreferences();

        self::assertIsArray($result);
        self::assertSame(['key1', 'key2'], array_keys($result));
        self::assertSame(['value1', 'value2'], array_values($result));
    }

    public function testSetProfilePreferences(): void
    {
        $request = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $response = <<<'JSON'
                        {
                            "key1": "value1",
                            "key2": "value2"
                        }
            JSON;

        $this->client
            ->method('put')
            ->willReturnMap([
                ['/profile/preferences', $request, new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $result = $profile->setProfilePreferences([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        self::assertIsArray($result);
        self::assertSame(['key1', 'key2'], array_keys($result));
        self::assertSame(['value1', 'value2'], array_values($result));
    }

    public function testDisable2FA(): void
    {
        $this->client
            ->method('post')
            ->willReturnMap([
                ['/profile/tfa-disable', [], new Response(200, [], null)],
            ])
        ;

        $profile = new Profile($this->client);

        $profile->disable2FA();

        self::assertTrue(true);
    }

    public function testEnable2FA(): void
    {
        $response = <<<'JSON'
                        {
                            "secret": "5FXX6KLACOC33GTC",
                            "expiry": "2018-03-01T00:01:01.000Z"
                        }
            JSON;

        $this->client
            ->method('post')
            ->willReturnMap([
                ['/profile/tfa-enable', [], new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $entity = $profile->enable2FA();

        self::assertInstanceOf(TwoFactorSecret::class, $entity);
        self::assertSame('5FXX6KLACOC33GTC', $entity->secret);
    }

    public function testConfirm2FA(): void
    {
        $request = [
            'tfa_code' => '213456',
        ];

        $response = <<<'JSON'
                        {
                            "scratch": "sample two factor scratch"
                        }
            JSON;

        $this->client
            ->method('post')
            ->willReturnMap([
                ['/profile/tfa-enable-confirm', $request, new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $scratch = $profile->confirm2FA('213456');

        self::assertSame('sample two factor scratch', $scratch);
    }

    public function testGetGrants(): void
    {
        $response = <<<'JSON'
                        {
                            "global": {
                                "add_linodes": true,
                                "add_longview": true,
                                "longview_subscription": true,
                                "account_access": "read_only",
                                "cancel_account": false,
                                "add_domains": true,
                                "add_stackscripts": true,
                                "add_nodebalancers": true,
                                "add_images": true,
                                "add_volumes": true
                            },
                            "linode": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "domain": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "nodebalancer": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "image": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "longview": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "stackscript": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ],
                            "volume": [
                                {
                                    "id": 123,
                                    "permissions": "read_only",
                                    "label": "example-entity"
                                }
                            ]
                        }
            JSON;

        $this->client
            ->method('get')
            ->willReturnMap([
                ['/profile/grants', [], [], new Response(200, [], $response)],
            ])
        ;

        $profile = new Profile($this->client);

        $entity = $profile->getGrants();

        self::assertInstanceOf(UserGrant::class, $entity);
        self::assertInstanceOf(GlobalGrant::class, $entity->global);
    }

    public function testGetGrantsUnrestricted(): void
    {
        $this->client
            ->method('get')
            ->willReturnMap([
                ['/profile/grants', [], [], new Response(204, [], null)],
            ])
        ;

        $profile = new Profile($this->client);

        $entity = $profile->getGrants();

        self::assertNull($entity);
    }
}
