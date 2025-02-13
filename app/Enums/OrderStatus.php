<?php

namespace App\Enums;

enum OrderStatus : string
{
  case PROCESSED = 'processed';
  case SHIPPED = 'shipped';
  case INTRANSIT = 'intransit';
  case DELIVERED = 'delivered';
  case PROCESSING = 'processing';
  case AWAITING_SHIPMENT = 'awaiting_shipment';



  
  public function isProcessed(): bool 
  {
      return $this === self::PROCESSED;
  }

  public function isShipped(): bool 
  {
      return $this === self::SHIPPED;
  }

  public function isIntransit(): bool 
  {
      return $this === self::INTRANSIT;
  }
  
  public function isDelivered(): bool 
  {
      return $this === self::DELIVERED;
  }


  public function isProcessing(): bool 
  {
      return $this === self::PROCESSING;
  }
  
  public function isAwaitingShipment(): bool 
  {
      return $this === self::AWAITING_SHIPMENT;
  }
  
}