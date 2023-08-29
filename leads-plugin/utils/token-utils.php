<?php
function getNoExpToken()
{
  if (!isset($_SESSION['token'])) {
    error_log('TOKEN DONT EXIST');
    wp_getDatafromLogin();
  }
  return $_SESSION['token'];
}
