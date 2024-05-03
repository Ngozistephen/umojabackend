<?php

namespace App\Enums;

enum PaymentStatus : string
{
  case PAID = 'paid';
  case PENDING = 'pending';

  


  public function isPaid(): bool 
  {
      return $this === self::PAID;
  }

  public function isPending(): bool 
  {
      return $this === self::PENDING;
  }

  
}
