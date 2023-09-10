<?php

namespace App\Services;

use Goutte\Client;

/**
 * Class ScrapMangaService
 * @package App\Services
 */
class ScrapMangaService
{

    private $url;
    
    private $client;

    public function __construct($url)
    {
        $this->url = $url;

        // Create a Goutte client
        $this->client = new Client();
    }

    public function scrapeManga()
    {
        // Make a request to the webpage
        $crawler = $this->client->request('GET', $this->url);

        // Extract title and description
        $title = $crawler->filter('title')->text();
        $description = $crawler->filter('meta[name="description"]')->attr('content');

        // // Download an image (e.g., the first image on the webpage)
        // $imageSrc = $crawler->filter('img')->first()->attr('src');
        // $imageData = file_get_contents($imageSrc);
        // $imageName = 'image_' . time() . '.jpg'; // Generate a unique image name
        // $imagePath = storage_path('app/public/' . $imageName);
        // file_put_contents($imagePath, $imageData);

        // Return the scraped data
        return [
            'title' => $title,
            'description' => $description,
            // 'image' => $imageName,
        ];
    }

    public function getTitle()
    {
        // Make a request to the webpage
        $crawler = $this->client->request('GET', $this->url);

        // Define an array of search criteria
        $searchCriteria = [
            // mangakakalot.com
            'ul.manga-info-text > li > h1', 
        ];

        // Initialize a variable to store the scraped content
        $scrapedContent = null;

        // Iterate through the search criteria
        foreach ($searchCriteria as $criteria) {
        
            // Use both tag name and class selector to target a specific element
            $element = $crawler->filter($criteria);

            // Check if the element exists
            if ($element->count() > 0) {
                // Scrape and use the content
                $scrapedContent = $element->text();
                break; // Break out of the loop after finding the first matching element
            }
        }

        // Check if content was found
        if ($scrapedContent !== null) {
            // Content was found, do something with it
            // echo "Scraped Content: " . $scrapedContent;
        
            return $scrapedContent;
        } 

        return;
    }

}
