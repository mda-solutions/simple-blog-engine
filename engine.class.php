<?php
  
class Engine
{
   
  public $settings_file = "settings.ini";
  public $settings;
  public $files;
  public $htmls = array();

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
   }

   public function getPosts()
   {     

      $folder = $this->settings["folder_entries"];
      //$cont   = 0;
      $posts  = new stdClass();

      foreach ($this->htmls as $html) 
      {
        $file = $folder . "/" . $html;

        if (file_exists($file)) 
        {      
          $post          = new stdClass();
          $post->title   = self::titlezr($html);
          $post->content = file_get_contents($file);

          $posts->$html = $post;
        }  
      }

      return json_encode($posts);
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

   public function getListPostsJSON()
   {      
      $folder = $this->settings["folder_entries"];
      $list   = new stdClass();

      foreach ($this->htmls as $html) 
      {
        $file = $folder . "/" . $html;
        if (file_exists($file)) 
        {      
          $list->$html->date    = date ($this->settings['date_format'], filemtime($file));          
          $list->$html->title   = self::titlezr($html);
        }         
      }

      return json_encode($list);
   }

}