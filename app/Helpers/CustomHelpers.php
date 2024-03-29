<?php

/**
 * @author Jeremy Layson <jeremy.b.layson@gmail.com>
 * @since 2020/11/24
 */
if (!function_exists('customResponse')) {
  function customResponse($data = NULL, $message = NULL)
  {
    return new \App\Helpers\ExtendedResponse($data, $message);
  }
}