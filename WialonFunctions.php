<?php

  // Интеграция CRM Clientbase с сервисом мониторинга транспорта Виалон (Wialon)
  // https://ClientbasePro.ru
  // docs.wialon.com, https://sdk.wialon.com/wiki/ru/sidebar/start

  // функция выполняет произвольный запрос к Wialon к сервису (string)$someService с параметрами (array)$params и (array)$toGETparams
function GetWialonData($someService='', $params=[], $toGETparams=[]) {
  if (!$someService) return false;
  $url = WIALON_URL;
  $url .= $someService;
  $url .= ($params) ? '&params='.json_encode($params) : '&params={}';
  if ($toGETparams) $url .= '&'.http_build_query($toGETparams);  
  $options = array('http'=>array("Content-Type: application/x-www-form-urlencoded"));
  $context = stream_context_create($options);
  return file_get_contents($url, false, $context);
}

  // функция возвращает Wialon Session ID
function GetWialonSID() {
  $answer = json_decode(GetWialonData('token/login',array('token'=>WIALON_TOKEN)), true); 
  if ($answer=$answer['eid']) return $answer;
  return false;
}

  // функция разлогинивает подключение к WIALON, возвращает bool результат
function LogoutWialon($SID='') {
  if (!$SID) return false;
  $answer = json_decode(GetWialonData('core/logout', '', array('sid'=>$SID)), true);
  if (0==$answer['error']) return true;
  return false;
}