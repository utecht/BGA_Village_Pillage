<?php
namespace VP\Core;
use villagepillage;

/*
 * Game: a wrapper over table object to allow more generic modules
 */
class Game
{
  public static function get()
  {
    return villagepillage::get();
  }
}
