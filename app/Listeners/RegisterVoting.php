<?php

namespace App\Listeners;

use App\Events\VotingFilled;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;

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

        $voting = $event->voting;
        $startTime = strtotime($voting->start_time)*1000;
        $endTime = strtotime($voting->end_time)*1000;
        $candidates = array_values(array_map(function ($candidate) {
            return $candidate->name.': '.$candidate->description;
        }, $voting->candidates->all()));
        $admittedVoters = array_values(array_map(function ($admittedVoter) {
            return $admittedVoter->user_id;
        }, $voting->admittedVoters->all()));
        
        $authorPublicKey = str_replace("\\n", "\n", env('ADMIN_PUBLIC_KEY'));
        $authorPrivateKey = str_replace("\\n", "\n", env('ADMIN_PRIVATE_KEY'));
        
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
