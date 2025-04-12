<?php

namespace App\Helpers;

class Help
{
   public static function maskMoneyReal($value)
   {
      return number_format($value, 2, ',', '.');
   }
}