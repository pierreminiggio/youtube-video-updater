<?php

namespace PierreMiniggio\YoutubeVideoUpdater;

use PierreMiniggio\YoutubeVideoUpdater\Exception\BadVideoIdException;
use RuntimeException;

class VideoUpdater
{

    /**
     * @param string[] $tags
     *
     * @throws BadVideoIdException
     * @throws RuntimeException
     */
    public function update(
        string $accessToken,
        string $videoId,
        string $title,
        string $description,
        array $tags,
        int $categoryId,
        bool $selfDeclaredMadeForKids
    ): void
    {
        $body = json_encode([
            'id' => $videoId,
            'snippet' => [
                'categoryId' => $categoryId,
                'description' => $description,
                'title' => $title,
                'tags' => $tags
            ],
            'status' => [
                'selfDeclaredMadeForKids' => $selfDeclaredMadeForKids
            ]
        ]);

        $curl = curl_init('https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cstatus');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body),
                'Authorization: Bearer ' . $accessToken
            ]
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            curl_close($curl);
            throw new RuntimeException('Curl error' . curl_error($curl));
        }

        $jsonResponse = json_decode($response);

        if ($jsonResponse === null) {
            curl_close($curl);
            throw new RuntimeException('Bad youtube API return');
        }

        if (
            ! empty($jsonResponse->error)
            && $jsonResponse->error->code === 403
        ) {
            curl_close($curl);
            throw new RuntimeException($jsonResponse->error->message);
        }

        if (
            ! empty($jsonResponse->error)
            && $jsonResponse->error->code === 404
        ) {
            curl_close($curl);
            throw new BadVideoIdException($jsonResponse->error->message);
        }

        if (! empty($jsonResponse->error)) {
            curl_close($curl);
            throw new RuntimeException($jsonResponse->error->message);
        }

        curl_close($curl);
    }
}
