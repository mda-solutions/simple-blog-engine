<?php

/**
 * 
 *     https://github.com/mda-solutions
 *
 * Licensed under the MIT License (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://opensource.org/licenses/MIT
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
  
class Engine
{
   
  public $settings_file = "settings.ini";
  public $htmls         = array();
  public $current_page;
  public $settings;
  public $files;
  public $total_items;

   function __construct() 
   {
     try
     {
      
      $this->getSettings();
      $this->getEntries();

     }catch(Exception $e)
     {
      echo $e->getMessage();
      die();
     }
   }

   private function getSettings()
   {
      $this->settings = parse_ini_file($this->settings_file);
      $this->checkSettings();
   }

   private function checkSettings()
   {
      if($this->settings["items_per_page"] <= 0)
      {
        throw new Exception("items_per_page no esta definido", 1);        
      }

      if($this->settings["folder_entries"] == "")
      {
        throw new Exception("folder_entries no esta definido", 1);        
      }   

      if($this->settings["date_format"] == "")
      {
        throw new Exception("date_format no esta definido", 1);        
      } 

      if($this->settings["order"] == "")
      {
        throw new Exception("order no esta definido", 1);        
      }        

      if($this->settings["base_url"] == "")
      {
        throw new Exception("base_url no esta definido", 1);
      }

      if($this->settings["blog_name"] == "")
      {
        throw new Exception("blog_name no esta definido", 1);
      }       

   }

   private function readDirectory()
   {
      if(!$this->files = scandir($this->settings["folder_entries"]))
      {
        throw new Exception("No se pudo leer el directorio " . $this->settings["folder_entries"], 1);         
      }
   }

   public function getEntries()
   {
      $this->readDirectory();
      foreach ($this->files as $file) 
      {
        $ext = end(explode(".", $file));
        if($ext == "html")
        {
          array_push($this->htmls, $file);  
        }         
      }

      $this->total_items = count($this->htmls);
      $this->orderHtmls();
   }

   public function orderHtmls()
   {
      $sorting_function = ($this->settings['order'] == "ascendente") ? 'sort' : 'rsort';
      $sorting_function($this->htmls);
   }

   private static function urlfy($string)
   {
      $string =   strtolower(trim($string));

      $string = str_replace(' ', '-', $string);
      $string = str_replace('á', 'a', $string);
      $string = str_replace('é', 'e', $string);
      $string = str_replace('í', 'i', $string);
      $string = str_replace('ó', 'o', $string);
      $string = str_replace('ú', 'u', $string);

      $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
      
      return urlencode($string);
   }

   private function getSlicedPostsJson($isjson = true)
   {

      $folder = $this->settings["folder_entries"];
      $items  = (int)$this->settings["items_per_page"];
      $cont   = ($this->current_page - 1) * $items;
      $posts  = new stdClass();
      $htmls  = $this->sliceHtmls();

      foreach ($htmls as $html) 
      {
        $file      = $folder . "/" . $html;
        $num       = $cont + 1;
        $item_page = round($num / $items);

        if (file_exists($file)) 
        {                
          $post          = new stdClass();
          $post->title   = self::titlezr($html);
          $post->content = file_get_contents($file);
          $post->date    = date ($this->settings['date_format'], filemtime($file));          
          $post->id      = sprintf('post_%s_%s', $item_page, $num);
          $post->hash    = sprintf('#post_%s_%s/%s', $item_page, $num, self::urlfy($post->title));

          $posts->$html = $post;

          $cont ++;
        }  
      }

      return ($isjson)? json_encode($posts) : $posts;
          
   }

   public function sliceHtmls($init = 0)
   {
      $items = $this->settings["items_per_page"];

      if($init == 0)
      {
        $init  = ($this->current_page - 1) * $items;  
      }
      
      return array_slice($this->htmls, $init, $items);
   }

   public function getRangePostsJson($page_from, $page_to)
   {

      $this->current_page = $page_from;

      $folder = $this->settings["folder_entries"];
      $items  = (int)$this->settings["items_per_page"];
      $cont   = 0;
      $posts  = new stdClass();

      $init   = ($page_from * $items);
      $diff   = $page_to - $page_from;
      $end    = ($diff == 1) ? $items : $items * $diff;

      $htmls  = array_slice($this->htmls, $init, $end);
      
   
      foreach ($htmls as $html) 
      {        
        $file      = $folder . "/" . $html;
        $num       = ($init + 1) + $cont;
        $item_page = round($num / 2);

        if (file_exists($file)) 
        {

          $post          = new stdClass();
          $post->title   = self::titlezr($html);
          $post->content = file_get_contents($file);
          $post->date    = date ($this->settings['date_format'], filemtime($file));          
          $post->id      = sprintf('post_%s_%s', $item_page , $num) ;
          $post->hash    = sprintf('#post_%s_%s/%s', $item_page, $num, self::urlfy($post->title));
          $posts->$html = $post;
          $cont ++;
        }  
      }

      return json_encode($posts);
   }

   public function getPostsJson($page, $all = false, $isjson = true)
   {     

      $this->current_page = $page;

      if(!$all)
      {
        return $this->getSlicedPostsJson($isjson);
      }

      $folder = $this->settings["folder_entries"];
      $items  = (int)$this->settings["items_per_page"];
      $cont   = 0;
      $posts  = new stdClass();

      foreach ($this->htmls as $html) 
      {
        $file      = $folder . "/" . $html;
        $num       = $cont + 1;
        $item_page = round($num / $items);

        if (file_exists($file)) 
        {                
          $post          = new stdClass();
          $post->title   = self::titlezr($html);
          $post->content = file_get_contents($file);
          $post->date    = date ($this->settings['date_format'], filemtime($file));          
          $post->id      = sprintf('post_%s_%s', $item_page, $num);
          $post->hash    = sprintf('#post_%s_%s/%s', $item_page, $num, self::urlfy($post->title));

          $posts->$html = $post;

          $cont ++;
        }  
      }

      return ($isjson)? json_encode($posts) : $posts;
   }

   public function getContentPostJSON($html)
   {
      $folder = $this->settings["folder_entries"];
      $file   = $folder . "/" . $html;
      $post   = new stdClass();

      if (file_exists($file)) 
      {
        if(!$post->content = file_get_contents($file))
        {
          throw new Exception("No se pudo leer el archivo " . $html, 1);        
        }
      }

      return json_encode($post);
   }

   public function getSettingsJSON()
   {
      return json_encode($this->settings);
   }

   public static function titlezr($title)
   {
      $elements = explode('.', $title);
      $words    = explode('_', $elements[0]);
      $titlezr  = '';
      $cont     = 0;

      foreach ($words as $word)
      {
        
        if($cont > 0)
        {
          $titlezr .= $word . ' ';
        }

        $cont ++;
      }

      return $titlezr;   
   }

   public function getFeedItems()
   {
      
      $items = "";

      $posts = $this->getPostsJson(1, true, false);
      foreach ($posts as $post) 
      {
        $item = self::getFeedItemTemplate();

        $item = str_replace('{TITLE}', $post->title, $item);
        $item = str_replace('{LINK}', $this->settings['base_url'], $item);
        $item = str_replace('{DATE}', $post->date, $item);
        $item = str_replace('{GUID}', $this->settings['base_url'] .  $post->hash, $item);
        $item = str_replace('{CONTENT}', htmlspecialchars($post->content), $item);
        $items .= $item;
      }

      return $items;
   }

   private static function getFeedItemTemplate()
   {

    $feed = 
    "
       <item> 
        <title>{TITLE}</title> 
        <link>{LINK}</link>
        <pubDate>{DATE}</pubDate> 
        <guid>{GUID}</guid> 
        <description>{CONTENT}</description> 
      </item>
    ";
      return $feed;
   }

}