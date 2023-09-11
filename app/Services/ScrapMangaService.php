<?php

namespace App\Services;

use Goutte\Client;
use App\Models\Manga;
use App\Models\Source;
use App\Models\ScanFilter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
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
        $url = $this->url;
        $crawler = $this->crawler;

        // Check if the URL already exists
        $urlExists = Source::published()->where('url', $url)->exists();

        if ($urlExists) {
            // The URL already exists in the database
            return [
                'already_exist' => true
            ];
        }

        $title = null;
        $alternativeTitle = null;
        $imagePath = null;

        $domainName = getDomainFromUrl($url);

        $scanFilter = ScanFilter::whereNotNull('title_filter')
            ->whereNotNull('alternative_title_filter')
            ->where('name', 'LIKE', '%' . $domainName . '%')
            ->first();

        // Source is not supported yet or invalid!
        if (!$scanFilter) {
            return [
                'invalid_url' => true
            ];
        }

        $titleElement = $crawler->filter($scanFilter->title_filter);
        
        if ($titleElement->count() > 0) {
            // Scrape and use the content
            $title = $titleElement->text();
        }
        
        $alternativeTitleElement = $crawler->filter($scanFilter->alternative_title_filter);
        
        if ($alternativeTitleElement->count() > 0) {
            // Scrape and use the content
            $alternativeTitle = $alternativeTitleElement->text();
        }

        // If image_filter is empty, means cant be scrape then use the default-image
        if (empty($scanFilter->image_filter)) {
            
            $imagePath= $this->imagePath();
        
        }else {

            // Download an image 
            $imageSrc = $crawler->filter($scanFilter->image_filter)->first()->attr('src');
            
            // Check if $imageSrc is not empty
            if (!empty($imageSrc)) {
                // Download the image and save it using Intervention\Image
                $image = Image::make($imageSrc);

                // Define the new width and height for the image
                $newWidth = 250; // Replace with your desired width
                $newHeight = 300; // Replace with your desired height
        
                // Resize the image while maintaining its aspect ratio
                $image->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
        
                $timestamp = now()->timestamp;
                $randomString = Str::random(10); // Generate a random string of 10 characters
                $imageName = $timestamp .'_'. $randomString; // Unique image name
                $imageName = 'scrap_'.md5($imageName) . '.jpg'; // Generate a unique image name with the JPG extension
        
                $imagePath = config('appsettings.manga_image_disk').'/' . config('appsettings.manga_image_destination_path').'/'.$imageName;
                
            } else {
                // Handle the case where $imageSrc is empty (no image found)
                $imagePath = $this->imagePath();
            
            }
        }
        
        
        // check $title to Manga title and alternative_title 
        $checkManga = Manga::where(function ($query) use ($title) {
            $query->where('title', 'like', '%' . $title . '%')
            ->orWhere('alternative_title', 'like', '%' . $title . '%');
        })->first(); // Retrieve the first matching record
        
        $newManga = null;
        if (!$checkManga) {
            // The title was not found in either column title/alternative, then insert record into Manga 
            // Define the data you want to insert, excluding the 'photo' attribute
            $data = [
                'url'               => $url,
                'title'             => $title,
                'alternative_title' => $alternativeTitle,
            ];

            // Create a new Manga model without triggering the mutator
            $newManga = new Manga($data);
            // Attempt to save the model and store the result in a variable
            $success = $newManga->save();

            
            // Check if the insertion was successful
            if ($success) {
                // Insertion was successful
                // Save the image to the storage directory as JPG
                Storage::put($imagePath, $image->stream('jpg', 90));
                
                // im using raw SQL query here to update the photo and to not trigger the mutator setPhotoAttribute.
                DB::table('mangas')
                ->where('id', $newManga->id) 
                ->update(['photo' => str_replace('public/', '', $imagePath)]);
            }
        } 


        if ($newManga !== null) {
            // Create a new Source record 
            $source = new Source([
                'manga_id'       => $newManga->id,
                'url'            => $url,
                'scan_filter_id' => $scanFilter->id,
                'published'      => true,
            ]);
            
            $result = $source->save();

            if ($result) {
                // Run the specified Artisan command without capturing output
                Artisan::call('winex:scan-chapters', [
                    '--mangaId' => $newManga->id, 
                ]);

            }

            return $result;
        }

        return;
    }

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

    private function imagePath()
    {
        return 'images/photo/default-image.jpg';
    }
}

// TODO:: NOTE dont forget when you insert remove Alternative text some website just hardcode the word and combine it 
    // TODO:: remove word Alternative and Updating if it's the first word in the paragraph
            // TODO:: Alternative : 
            // TODO:: Alternative 
            // TODO:: updating
            // TODO:: Updating
            // TODO:: [All Chapters]
    // TODO:: check sources and change readmanganato.com filter to manganato
// TODO:: in production
    // TODO:: add mangas_add_manga permission, roles and to user admin(me) dont put to normal user yet.
    // TODO:: dont forget to run iseeder but run the schedule run backup first if anything happens.
    // TODO:: test it try to add manga. also add manga that doesn't support manga scrap for image or source that have empty/null image_filter
    // TODO:: then add the permission to normal user