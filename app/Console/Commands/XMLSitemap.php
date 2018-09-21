<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Products;
use App\Models\Categories;
use App\Models\News;
use App\Models\HTMLContent;

class XMLSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'xmlsitemap'; //название нашей команды

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generation Sitemap.xml';//описание нашей команды

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //тут тело как-раз нашей функции
        //$site_url = env('APP_URL');//уберите лишние пробелы
        $site_url = 'https://lab-oborud.com';//уберите лишние пробелы
        $base = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            </urlset>';
        $xmlbase = new \SimpleXMLElement($base);
        $row  = $xmlbase->addChild("url");
        $row->addChild("loc",$site_url);
        $row->addChild("lastmod",date("c"));
        $row->addChild("changefreq","monthly");
        $row->addChild("priority","1");

        $row  = $xmlbase->addChild("url");
        $row->addChild("loc",$site_url.'/news');
        $row->addChild("lastmod",date("c"));
        $row->addChild("changefreq","monthly");
        $row->addChild("priority","0.9");

        //выбираем нужные нам записи из базы данных
        foreach (Products::where('stock', 1)->get() as $result) {
            $row  = $xmlbase->addChild("url");
            $row->addChild("loc",$site_url.'/product/'.$result->url_alias);
            if(empty($result->updated_at)) {
                if(empty($result->created_at)){
                    $row->addChild("lastmod", date("Y-m-d\TH:i:sP"));
                }else{
                    $row->addChild("lastmod", $result->created_at->format("Y-m-d\TH:i:sP"));
                }
            }else
                $row->addChild("lastmod",$result->updated_at->format("Y-m-d\TH:i:sP"));
            $row->addChild("changefreq","monthly");
            $row->addChild("priority","0.8");
        }
        foreach (Categories::where('status', 1)->get() as $result) {
            $row  = $xmlbase->addChild("url");
            $row->addChild("loc",$site_url.'/categories/'.$result->url_alias);
            $row->addChild("lastmod",$result->created_at->format("Y-m-d\TH:i:sP"));
            $row->addChild("changefreq","monthly");
            $row->addChild("priority","0.9");
        }
        foreach (News::where('published', 1)->get() as $result) {
            $row  = $xmlbase->addChild("url");
            $row->addChild("loc",$site_url.'/news/'.$result->url_alias);
            $row->addChild("lastmod",$result->created_at->format("Y-m-d\TH:i:sP"));
            $row->addChild("changefreq","monthly");
            $row->addChild("priority","0.8");
        }
        foreach (HTMLContent::where('status', 1)->get() as $result) {
            $row  = $xmlbase->addChild("url");
            $row->addChild("loc",$site_url.'/page/'.$result->url_alias);
            $row->addChild("lastmod",$result->created_at->format("Y-m-d\TH:i:sP"));
            $row->addChild("changefreq","monthly");
            $row->addChild("priority","0.9");
        }

        //укажите путь куда нужно сохранять файл
        $xmlbase->saveXML(public_path()."/sitemap.xml");
    }
}
