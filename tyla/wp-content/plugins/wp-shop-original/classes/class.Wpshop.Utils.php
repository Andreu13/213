<?php 
class Wpshop_Utils
{
  /**
    * @param string $type может принимать следующие значени€ en и ru
    * @param string строка в формате en или ru
    * @return string ¬озвращает дату в нужном формате, либо ¬ызывает Exeption, если преобразование прошло безуспешно
  */
  public static function checkDate($type,$dateString)
  {
    $currentFormat = '';
    $tmp = array();
    
    if (preg_match("/^(\d+)\.(\d+)\.(\d+)$/",$dateString,$tmp))
    {
      $currentFormat = 'ru';
    }
    else if (preg_match("/^(\d+)\-(\d+)\-(\d+)$/",$dateString,$tmp))
    {
      $currentFormat = 'en';
    }
    
    
    if ($type == $currentFormat)
    {
      return $dateString;
    }
    if ($currentFormat == '')
    {
      throw new Exception();
    }
    
    $newString = '';
    if ($type=="ru")
    {
      $newString = "{$tmp[3]}.{$tmp[2]}.{$tmp[1]}";	
    }
    
    if ($type == "en")
    {
      $newString = "{$tmp[3]}-{$tmp[2]}-{$tmp[1]}";
    }
    
    if ($newString != '')
    {
      return $newString;
    }
    else
    {
      throw new Exception();
    }	
  }
  
  public static function wpshop_link_encode($data){
    $home = get_settings('home');
    preg_match_all('/href=[\"\']((https?|ftp):\/\/\S*?)[\"\']/ui',$data,$arr);
    
    if(get_option("wp-shop_relink") === false||get_option("wp-shop_relink") ==''){ 
      update_option("wp-shop_relink",dechex(rand(0x1000,0xFFFFFF)));
    }
    
    $relink = get_option("wp-shop_relink");
    
    if(stripos($arr[0][0],$home)!==0){ // ext
      $tmp =$arr[0][0];
      $tmp = str_replace($arr[1][0],$home.'/'.$relink.'/'.base64_encode($arr[1][0]).'/',$tmp);
    }
    $data = str_replace($arr[0][0],$tmp,$data);
    
    return $data; 
  }
}