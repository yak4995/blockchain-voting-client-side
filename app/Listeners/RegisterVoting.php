<?php

namespace App\Listeners;

use App\Events\VotingFilled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;

use App\Voting;

class RegisterVoting implements ShouldQueue
{

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VotingFilled  $event
     * @return void
     */
    public function handle(VotingFilled $event)
    {

        $http = new HttpClient;

        $response = $http->post(env('BLOCKCHAIN_SERVER_URL').'/auth/token', [
            GuzzleRequestOptions::JSON => [
                'name' => env('BLOCKCHAIN_SERVER_NAME'),
                'key' => env('BLOCKCHAIN_SERVER_KEY')
            ]
        ]);

        $accessToken = json_decode((string) $response->getBody(), true)['accessToken'];

        $voting = $event->getVoting();
        $startTime = strtotime($voting->start_time)*1000;
        $endTime = strtotime($voting->end_time)*1000;
        $candidates = array_values(array_map(function ($candidate) {
            return $candidate->name.': '.$candidate->description;
        }, $voting->candidates->all()));
        $admittedVoters = array_values(array_map(function ($admittedVoter) {
            return $admittedVoter->user_id;
        }, $voting->admittedVoters->all()));
        
        $authorPublicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA57PbiWYcl0r71IxPgrZM\nleAWZfN3WGfeyIs+Q9xBwsY0v6toeAvk7gjsiyD/FE/fCS9UhHQ7kWaHF/p8bsGi\ndMzaHa3qh358PEdrjcSMAJYLp0ZCUBND5VZWCUxSXBqYcfHdn2Zin9qTd7L7wymB\ntB2DGAVv1TV31h+0VyPH3dhPn5zaZJLkAl+b/QuD8YezXnVFxqmkOy/GmOJJxRWC\n3vbpeaQioS8idXrzSHXvOtmomDYtPWuS2igIMNValGc/6gVNbexvXm1lJJ1ugVs4\nDYgJSQ1Yf1jK5rpjwYp4MasqeycaisUaa1c8VJVWCLPf9pBttb+g8K8b2dCaJZ45\n1wIDAQAB\n-----END PUBLIC KEY-----";
        $authorPrivateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDns9uJZhyXSvvU\njE+CtkyV4BZl83dYZ97Iiz5D3EHCxjS/q2h4C+TuCOyLIP8UT98JL1SEdDuRZocX\n+nxuwaJ0zNodreqHfnw8R2uNxIwAlgunRkJQE0PlVlYJTFJcGphx8d2fZmKf2pN3\nsvvDKYG0HYMYBW/VNXfWH7RXI8fd2E+fnNpkkuQCX5v9C4Pxh7NedUXGqaQ7L8aY\n4knFFYLe9ul5pCKhLyJ1evNIde862aiYNi09a5LaKAgw1VqUZz/qBU1t7G9ebWUk\nnW6BWzgNiAlJDVh/WMrmumPBingxqyp7JxqKxRprVzxUlVYIs9/2kG21v6DwrxvZ\n0JolnjnXAgMBAAECggEASC/O2/XGPpSL9OJp+y1UmvUfxU+fBRoHXK+VDItYqZga\n4wRCHfSGtGpvV8FF90wTDseCK2oTDO/GcwAFOHR3arBP3CNNCD2t8xHFPnvXqm8U\n3l6TVmNKKe9GCsuOdUeL6yQRihHZ9Dei7g4DRgBuenEfYKKA/woTddCW3Pc207Ry\n24ViO1RlnuHU9ZzS+meJAdBYvish2Q+z885Zn40moSrXNxIo3s4dckoxX3Vlzgnt\niLMsVwtQ12hEvVq8YmiJ2OQPwuudW4uM6F4smG1XCiWFZ6TY/RFh2C0MrYKenzXB\nCEf1iUaOsZoOLL35R9WnPiitDOYR7ArQx8TxIL8QcQKBgQD/iMNz1VrJ4e7uw5A9\nX/bW1QHmXGFZTNet5DGgUsm5L8NBTsBo6xCG5Ik5FxwSw5FP0joPy3GJJgDAS3Zf\nJ4M6OiP18io5QyG9uj+/CqM9ISc4x0atrx2nSvOSVyK7QBx5SMlTvcFoZjlgrUtZ\n/4Ku7DyClL8JF3pY9BiV75VJnQKBgQDoH/lM323aV7bdunMeW0UjvFAPEMNQnu8w\n9dvMtFYXfa75tywLjA4N7q66jFeDZ0Mvvcawtp0iEYWwl7etzRICG7uej7Op8Vty\nNJbyW9aN7L9OX5grJyogTdRZK2xgHVw8hKDBsef8pNc/Prh3/RGbg9jBaG32yNKA\n6k+qESvBAwKBgQCQ5dI+2pqSo4TC6y3dP49OnpZnM7cX1hTuy9jAGnG8irLjU26T\nj8ddVjXho5MNqMu7QXAfCLOmm2ANqjzDFDq7R8Cgc+MxeTmmxffjsnqB7Uy6S3Vu\ng0ADXuLi9noBSAddVsKis5T6SAz9Hwb9T3+hBOADA6mX1DJSQoe2bZZvmQKBgQCT\njE9xd8xiL8NDadLnBukJ8BeLnAIq6vvryTvwAOmAgRmKDc7ngB0m6gMS/UZbdnYU\nkLMNfOag0zaBq87LoUDDKlG2Vm3DpnGURK12XL4i9Mwdy1H0jC6Q3igOjjWTWtZY\neY2d0bI+u6E+yGWFj81zZvmO5wyPA9QasdX1qnh/dQKBgAdDo4C+Wuebo0ZSy9sa\n9ziwiOA7rfvAXnSs6MPQGWRUyrTPm7pv//kcnNweXDV1VJOQkMOk40LTrpu1s68l\nJH6j4deaE11pDCoVLyCsWzO1Fyp6FMEeyJ9gtORQRRecS66wuOukGH3V2icE0ib4\nuzHaHCoSdDVCqQkRrQOeLOyj\n-----END PRIVATE KEY-----";
        
        //получить публичный ключ выборов
        $response = $http->request('GET', env('BLOCKCHAIN_SERVER_URL').'/crypto/get-voting-pkey', [
            'headers' => ['Authorization' => 'Bearer '.$accessToken]
        ]);
        $votingPublicKey = (string) $response->getBody();

        //получить хеш
        $votingHashObjDTO = [
            "msg" => [
                "parentHash" => "",
                "authorPublicKey" => $authorPublicKey,
                "type" => 1,
                "votingDescription" => $voting->description,
                "startTime" => $startTime,
                "endTime" => $endTime,
                "candidates" => $candidates,
                "admittedVoters" => $admittedVoters,
                "registeredVoters" => [],
                "votingPublicKey" => $votingPublicKey,
                "admittedUserPublicKey" => "",
                "selectedVariant" => ""
            ]
        ];
        $response = $http->post(env('BLOCKCHAIN_SERVER_URL').'/crypto/get-object-hash', [
            GuzzleRequestOptions::JSON => $votingHashObjDTO
        ]);
        $hash = (string) $response->getBody();

        //получить подпись
        $votingSignObjDTO = [
            "message" => [
                "parentHash" => "",
                "authorPublicKey" => $authorPublicKey,
                "type" => 1,
                "votingDescription" => $voting->description,
                "startTime" => $startTime,
                "endTime" => $endTime,
                "candidates" => $candidates,
                "admittedVoters" => $admittedVoters,
                "registeredVoters" => [],
                "votingPublicKey" => $votingPublicKey,
                "admittedUserPublicKey" => "",
                "selectedVariant" => ""
            ],
            "privateKey" => $authorPrivateKey
        ];
        $response = $http->post(env('BLOCKCHAIN_SERVER_URL').'/crypto/sign-object', [
            GuzzleRequestOptions::JSON => $votingSignObjDTO
        ]);
        $signature = (string) $response->getBody();

        //зарегистрировать выборы, проверить ответ и выставить флаг
        $votingDTO = [
            "hash" => $hash,
            "parentHash" => "",
            "authorPublicKey" => $authorPublicKey,
            "signature" => $signature,
            "type" => 1,
            "votingDescription" => $voting->description,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "candidates" => $candidates,
            "admittedVoters" => $admittedVoters,
            "registeredVoters" => [],
            "votingPublicKey" => $votingPublicKey,
            "admittedUserPublicKey" => "",
            "selectedVariant" => ""
        ];
        $response = $http->request('POST', env('BLOCKCHAIN_SERVER_URL').'/nodes/create-chain', [
            'headers' => ['Authorization' => 'Bearer '.$accessToken],
            GuzzleRequestOptions::JSON => $votingDTO
        ]);
        $createdVoting = json_decode((string) $response->getBody(), true);
        if (isset($createdVoting['hash'])) {
            $voting->is_published = true;
            $voting->save();
        }
    }
}
