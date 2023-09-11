<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScanFiltersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Disable the foreign key constraint
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        \DB::table('scan_filters')->delete();
        
        \DB::table('scan_filters')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'https://www.readmng.com/',
                'filter' => '#chapters-tabContent a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:26:14',
                'updated_at' => '2023-09-11 11:33:59',
                'title_filter' => 'div.titleArea h1',
                'alternative_title_filter' => 'div.infox span',
                'image_filter' => 'div.thumbook img',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'https://mangakakalot.com/',
                'filter' => '.chapter-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:13',
                'updated_at' => '2023-09-11 10:24:42',
                'title_filter' => 'li > h1',
                'alternative_title_filter' => 'li > h2.story-alternative',
                'image_filter' => 'div.manga-info-pic img',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'https://www.topmanhua.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:50',
                'updated_at' => '2023-09-10 16:27:21',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => NULL,
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'https://mangatx.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 16:37:10',
                'updated_at' => '2023-09-10 15:19:33',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => NULL,
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'https://readmanganato.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 16:57:09',
                'updated_at' => '2023-09-11 10:46:55',
                'title_filter' => 'div.story-info-right h1',
                'alternative_title_filter' => 'table.variations-tableInfo h2',
                'image_filter' => 'span.info-image img.img-loading',
            ),
            5 => 
            array (
                'id' => 8,
                'name' => 'https://www.mangatown.com/',
                'filter' => '.chapter_list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-06 22:59:23',
                'updated_at' => '2023-09-10 16:16:37',
                'title_filter' => 'h1.title-top',
            'alternative_title_filter' => 'li:contains("Alternative Name:")',
                'image_filter' => NULL,
            ),
            6 => 
            array (
                'id' => 9,
                'name' => 'https://mangaweb.xyz/',
                'filter' => '.sub-chap-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-07 12:51:12',
                'updated_at' => '2023-09-11 10:43:45',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => 'div.summary_image img.img-responsive',
            ),
            7 => 
            array (
                'id' => 10,
                'name' => 'https://mangabuddy.com/',
                'filter' => '.chapter-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-16 03:39:28',
                'updated_at' => '2023-09-10 12:17:08',
                'title_filter' => 'div.name.box h1',
                'alternative_title_filter' => 'div.name.box h2',
                'image_filter' => NULL,
            ),
            8 => 
            array (
                'id' => 11,
                'name' => 'https://fanfox.net/',
                'filter' => '.detail-main-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-16 04:19:09',
                'updated_at' => '2023-09-10 15:25:34',
                'title_filter' => 'p.detail-info-right-title span.detail-info-right-title-font',
                'alternative_title_filter' => '#fanfox-no-alternative-title',
                'image_filter' => NULL,
            ),
            9 => 
            array (
                'id' => 13,
                'name' => 'https://www.foxaholic.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-30 05:56:25',
                'updated_at' => '2023-09-10 15:46:30',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => NULL,
            ),
            10 => 
            array (
                'id' => 14,
                'name' => 'https://lightnovelreader.org/',
                'filter' => '.novels-detail-chapters a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-30 06:14:16',
                'updated_at' => '2023-09-11 10:08:45',
                'title_filter' => 'h2.max-caracter-2',
            'alternative_title_filter' => 'li:contains("Alternative Names:") div.novels-detail-right-in-right span',
                'image_filter' => 'div.novels-detail-left > img',
            ),
            11 => 
            array (
                'id' => 15,
                'name' => 'https://flamescans.org/',
                'filter' => '.inepcx a',
                'deleted_at' => NULL,
                'created_at' => '2022-05-01 17:32:25',
                'updated_at' => '2023-09-11 10:05:56',
                'title_filter' => 'h1.entry-title',
                'alternative_title_filter' => 'div.desktop-titles',
                'image_filter' => 'div.thumb > img',
            ),
            12 => 
            array (
                'id' => 16,
                'name' => 'https://manga1st.online/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-05-01 17:54:41',
                'updated_at' => '2023-09-10 12:12:47',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => NULL,
            ),
            13 => 
            array (
                'id' => 17,
                'name' => 'https://mangamtl.com/',
                'filter' => 'ul.clstyle a',
                'deleted_at' => NULL,
                'created_at' => '2022-05-06 12:03:02',
                'updated_at' => '2023-09-11 10:36:43',
                'title_filter' => 'h1.entry-title',
                'alternative_title_filter' => 'div.wd-full span',
                'image_filter' => 'div.thumbook div.thumb img',
            ),
            14 => 
            array (
                'id' => 19,
                'name' => 'https://chapmanganato.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2022-12-19 15:13:16',
                'updated_at' => '2023-09-11 08:33:31',
                'title_filter' => 'div.story-info-right h1',
                'alternative_title_filter' => 'table.variations-tableInfo h2',
                'image_filter' => 'span.info-image > img',
            ),
            15 => 
            array (
                'id' => 20,
                'name' => 'https://mangaclash.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-12-30 20:28:51',
                'updated_at' => '2023-09-10 15:29:44',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => 'div.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => NULL,
            ),
            16 => 
            array (
                'id' => 22,
                'name' => 'https://scansraw.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2023-01-24 10:26:38',
                'updated_at' => '2023-09-11 11:24:09',
                'title_filter' => '.post-title h1',
            'alternative_title_filter' => '.post-content_item:contains("Alternative") div.summary-content',
                'image_filter' => 'div.summary_image img.img-responsive',
            ),
            17 => 
            array (
                'id' => 24,
                'name' => 'https://chapmanganelo.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2023-03-26 07:00:30',
                'updated_at' => '2023-09-11 09:33:17',
                'title_filter' => 'div.story-info-right h1',
                'alternative_title_filter' => 'table.variations-tableInfo h2',
                'image_filter' => 'span.info-image > img',
            ),
            18 => 
            array (
                'id' => 25,
                'name' => 'https://lnreader.org/',
                'filter' => '.novels-detail-chapters a',
                'deleted_at' => NULL,
                'created_at' => '2023-06-14 08:22:38',
                'updated_at' => '2023-09-11 10:10:05',
                'title_filter' => 'h2.max-caracter-2',
            'alternative_title_filter' => 'li:contains("Alternative Names:") div.novels-detail-right-in-right span',
                'image_filter' => 'div.novels-detail-left > img',
            ),
            19 => 
            array (
                'id' => 26,
                'name' => 'https://manganato.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2023-06-28 07:23:08',
                'updated_at' => '2023-09-11 10:38:29',
                'title_filter' => 'div.story-info-right h1',
                'alternative_title_filter' => 'table.variations-tableInfo h2',
                'image_filter' => 'span.info-image img',
            ),
            20 => 
            array (
                'id' => 27,
                'name' => 'https://readlightnovel.online/',
                'filter' => '.novels-detail-chapters a',
                'deleted_at' => NULL,
                'created_at' => '2023-07-05 21:06:21',
                'updated_at' => '2023-09-11 10:44:26',
                'title_filter' => 'h2.max-caracter-2',
            'alternative_title_filter' => 'li:contains("Alternative Names:") div.novels-detail-right-in-right span',
                'image_filter' => 'div.novels-detail-left > img',
            ),
            21 => 
            array (
                'id' => 28,
                'name' => 'https://www.mangageko.com/',
                'filter' => '.chapter-list a',
                'deleted_at' => NULL,
                'created_at' => '2023-07-07 10:08:09',
                'updated_at' => '2023-09-10 15:54:21',
                'title_filter' => '.main-head h1[itemprop="name"]',
                'alternative_title_filter' => '.main-head h2.alternative-title',
                'image_filter' => NULL,
            ),
            22 => 
            array (
                'id' => 29,
                'name' => 'https://asuracomics.com/',
                'filter' => 'ul.clstyle a',
                'deleted_at' => NULL,
                'created_at' => '2023-09-09 14:15:34',
                'updated_at' => '2023-09-11 09:31:44',
                'title_filter' => 'h1.entry-title',
            'alternative_title_filter' => 'div.wd-full b:contains("Alternative Title") + span',
                'image_filter' => '.thumb img[src]',
            ),
            23 => 
            array (
                'id' => 30,
                'name' => 'https://readlightnovel.app/',
                'filter' => '.novels-detail-chapters a',
                'deleted_at' => NULL,
                'created_at' => '2023-09-10 11:38:18',
                'updated_at' => '2023-09-11 10:44:04',
                'title_filter' => 'h2.max-caracter-2',
            'alternative_title_filter' => 'li:contains("Alternative Names:") div.novels-detail-right-in-right span',
                'image_filter' => 'div.novels-detail-left > img',
            ),
        ));
        
        // Enable the foreign key constraint
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}