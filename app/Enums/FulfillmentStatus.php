<?php

namespace App\Enums;

enum FulfillmentStatus : string
{
  case FULFILLED = 'fulfilled';
  case UNFULFILLED = 'unfulfilled';
  case CANCELLED = 'cancelled';
  

  public function isFulfilled(): bool 
  {
      return $this === self::FULFILLED;
  
  }

  public function isUnfulfilled(): bool 
  {
      return $this === self::UNFULFILLED;
  }

  public function isCancelled(): bool 
  {
      return $this === self::CANCELLED;
  }
}