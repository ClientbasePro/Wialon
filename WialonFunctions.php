<?php

  // Интеграция CRM Clientbase с сервисом мониторинга транспорта Виалон (Wialon)
  // https://ClientbasePro.ru
  // docs.wialon.com, https://sdk.wialon.com/wiki/ru/sidebar/start

  // функция выполняет произвольный запрос к Wialon к сервису (string)$someService с параметрами (array)$params и (array)$toGETparams на URL $someURL
function GetWialonData($someService='', $params=[], $toGETparams=[], $someURL='') {
  if (!$someService) return false;
  $url = ($someURL) ? $someURL : WIALON_URL;
  if (!$url || 'WIALON_URL'==$url) return false;
  $url .= $someService;
  $url .= ($params) ? '&params='.json_encode($params) : '&params={}';
  if ($toGETparams) $url .= '&'.http_build_query($toGETparams);  
  $options = array('http'=>array("Content-Type: application/x-www-form-urlencoded"));
  $context = stream_context_create($options);
  return file_get_contents($url, false, $context);
}

  // функция возвращает Wialon Session ID
function GetWialonSID($token, $someURL='') {
  if (!$token && defined(WIALON_TOKEN)) $token = WIALON_TOKEN;
  if (!$token) return false;
  $answer = json_decode(GetWialonData('token/login',array('token'=>$token), 0, $someURL), true); 
  if ($answer_=$answer['eid']) return $answer_;
  return false;
}

  // функция разлогинивает подключение к WIALON, возвращает bool результат
function LogoutWialon($SID='', $someURL='') {
  if (!$SID) return false;
  $answer = json_decode(GetWialonData('core/logout', 0, array('sid'=>$SID), $someURL), true);
  if (0==$answer['error']) return true;
  return false;
}