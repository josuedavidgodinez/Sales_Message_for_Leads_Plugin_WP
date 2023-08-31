<?php
function getNoExpToken()
{
  if (!isset($_SESSION['token'])) {
    wp_getDatafromLogin();
  }
  return $_SESSION['token'];
}
