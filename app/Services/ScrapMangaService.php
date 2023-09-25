<?php

namespace App\Services;

use Goutte\Client;
use App\Models\User;
use App\Models\Manga;
use App\Models\Source;
use App\Models\ScanFilter;
use Illuminate\Support\Str;
use App\Events\NewSourceAdded;
use Illuminate\Support\Facades\DB;
use App\Events\NewMangaOrNovelAdded;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ContactUsNotification;
use Illuminate\Support\Facades\Notification;
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

        $domainName = getDomainFromUrl($url);

        $scanFilter = ScanFilter::whereNotNull('title_filter')
            ->whereNotNull('alternative_title_filter')
            ->where('name', 'LIKE', '%' . $domainName . '%')
            ->first();

        // Source is not supported yet or invalid!
        if (!$scanFilter) {

            $this->notifyMeForInvalidUrl('Source is not supported');

            return [
                'invalid_url' => true
            ];
        }

        $title = $this->getText($scanFilter->title_filter);
        $alternativeTitle = $this->getText($scanFilter->alternative_title_filter);
        
        // if title is empty temporarily block by the site
        if (!$title) {

            $this->notifyMeForInvalidUrl('Null Title');

            return [
                'invalid_url' => true
            ];
        }
        
        // check $title to Manga title and alternative_title 
        $manga = Manga::where(function ($query) use ($title) {
            $query->where('title', 'like', '%' . $title . '%')
            ->orWhere('alternative_title', 'like', '%' . $title . '%');
        })->first(); // Retrieve the first matching record
        
        if (!$manga) {
            // The title was not found in either column title/alternative, then insert record into Manga 
            // Define the data you want to insert, excluding the 'photo' attribute
            $data = [
                'url'               => $url,
                'title'             => $title,
                'alternative_title' => $alternativeTitle,
            ];

            // Create a new Manga model without triggering the mutator
            $manga = new Manga($data);
            $success = $manga->save();
            
            if ($success) {
                // alert admin for new manga added
                event(new NewMangaOrNovelAdded($manga));
            }
        } 

        if ($manga !== null) {
            // if empty/null $manga->photo or contains default because of accessor then upload img, if URL can scrape img
            if ($manga->photo == null || stringContains($manga->photo, 'default-image.jpg')) {
                $imagePath = $this->downloadImage($scanFilter->image_filter);
            
                if ($imagePath != null) {
                    // im using raw SQL query here to update the photo and to not trigger the mutator setPhotoAttribute.
                    DB::table('mangas')
                    ->where('id', $manga->id) 
                    ->update(['photo' => str_replace('public/', '', $imagePath)]);
                }
            }

            // Create a new Source record 
            $source = new Source([
                'manga_id'       => $manga->id,
                'url'            => $url,
                'scan_filter_id' => $scanFilter->id,
                'published'      => true,
            ]);
            
            $result = $source->save();

            if ($result) {
                // alert admin for new source added
                event(new NewSourceAdded($source)); 

                // Run the specified Artisan command without capturing output
                Artisan::call('winex:scan-chapters', [
                    '--mangaId' => $manga->id, 
                ]);
                

            }

            return $result;
        }

        return;
    }

    public function downloadImage($filter)
    {
        // Download an image 
        if (!empty($filter)) {
            $imageSrc = $this->crawler->filter($filter)->first()->attr('src');
            
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
                
                // Save the image to the storage directory as JPG
                Storage::put($imagePath, $image->stream('jpg', 90));
                
                return $imagePath;
            }
        }

        return;
    }

    public function getText($filter)
    {
        $element = $this->crawler->filter($filter);
        
        
        if ($element->count() > 0) {
            // Scrape and use the content
            $text = $element->text();
        
            // Words to remove
            $wordsToRemove = [
                'Alternative :', 
                'Alternative', 
                'updating', 
                'Updating', 
                '[All Chapters]', 
            ];

            // Replace words with an empty string
            $filteredText = str_replace($wordsToRemove, '', $text);


            return $filteredText;
        }
        
        return;
    }

    public function notifyMeForInvalidUrl($type)
    {
        $data = [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
            'message' => $type .': '.$this->url,
        ];

        // debug($data);
        
        // send notification
        $usersWithAdminPermission = User::permission('admin_received_contact_us')->get();
        Notification::send($usersWithAdminPermission, new ContactUsNotification($data));
    }

}