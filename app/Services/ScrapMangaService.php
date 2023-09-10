<?php

namespace App\Services;

use Goutte\Client;

/**
 * Class ScrapMangaService
 * @package App\Services
 */
class ScrapMangaService
{

    public function scrapeManga($url)
    {
        // Create a Goutte client
        $client = new Client();

        // Make a request to the webpage
        $crawler = $client->request('GET', $url);

        // Extract title and description
        $title = $crawler->filter('title')->text();
        $description = $crawler->filter('meta[name="description"]')->attr('content');

        // Download an image (e.g., the first image on the webpage)
        $imageSrc = $crawler->filter('img')->first()->attr('src');
        $imageData = file_get_contents($imageSrc);
        $imageName = 'image_' . time() . '.jpg'; // Generate a unique image name
        $imagePath = storage_path('app/public/' . $imageName);
        file_put_contents($imagePath, $imageData);

        // Return the scraped data
        return [
            'title' => $title,
            'description' => $description,
            'image' => $imageName,
        ];
    }

}
