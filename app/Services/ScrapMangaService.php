<?php

namespace App\Services;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class ScrapMangaService
 * @package App\Services
 */
class ScrapMangaService
{

    private $url;
    
    private $client;

    private $crawler;

    public function __construct($url)
    {
        $this->url = $url;

        // $this->client = new Client();
        
        // Disable SSL certificate validation for this request (not recommended for production)
        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
    
        // Make a request to the webpage
        $this->crawler = $this->client->request('GET', $this->url);

        
    }

    public function scrapeManga()
    {
        $crawler = $this->crawler;

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

    // TODO:: NOTE dont forget when you insert remove Alternative text some website just hardcode the word and combine it 
    // TODO:: remove word Alternative and Updating if it's the first word in the paragraph
            // TODO:: Alternative 
            // TODO:: updating
            // TODO:: Updating
            // TODO:: [All Chapters]
    // TODO:: check sources and change readmanganato.com filter to manganato
    public function getText($criteria)
    {
        $crawler = $this->crawler;

        // Initialize a variable to store the scraped content
        $scrapedContent = null;

        // Use both tag name and class selector to target a specific element
        $element = $crawler->filter($criteria);

        // Check if the element exists
        if ($element->count() > 0) {
            // Scrape and use the content
            $scrapedContent = $element->text();
        }

        // Check if content was found
        if ($scrapedContent !== null) {
            // Content was found, do something with it
            return $scrapedContent;
        } 

        return;
    }

}
