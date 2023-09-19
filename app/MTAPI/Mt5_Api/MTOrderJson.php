<?php 
namespace MTAPI\Mt5_Api;

class MTOrderJson
  {
  /**
   * Get MTOrder from json object
   * @param object $obj
   * @return MTOrder
   */
  public static function GetFromJson($obj)
    {
    if ($obj == null) return null;
    $info = new MTOrder();
    //---
    $info->Order = (int)$obj->Order;
    $info->ExternalID = (string)$obj->ExternalID;
    $info->Login = (int)$obj->Login;
    $info->Dealer = (int)$obj->Dealer;
    $info->Symbol = (string)$obj->Symbol;
    $info->Digits = (int)$obj->Digits;
    $info->DigitsCurrency = (int)$obj->DigitsCurrency;
    $info->ContractSize = (float)$obj->ContractSize;
    $info->State = (int)$obj->State;
    $info->Reason = (int)$obj->Reason;
    $info->TimeSetup = (int)$obj->TimeSetup;
    $info->TimeExpiration = (int)$obj->TimeExpiration;
    $info->TimeDone = (int)$obj->TimeDone;
    $info->TimeSetupMsc = (int)$obj->TimeSetupMsc;
    $info->TimeDoneMsc = (int)$obj->TimeDoneMsc;
    $info->ModifyFlags = (int)$obj->ModifyFlags;
    $info->Type = (int)$obj->Type;
    $info->TypeFill = (int)$obj->TypeFill;
    $info->TypeTime = (int)$obj->TypeTime;
    $info->PriceOrder = (float)$obj->PriceOrder;
    $info->PriceTrigger = (float)$obj->PriceTrigger;
    $info->PriceCurrent = (float)$obj->PriceCurrent;
    $info->PriceSL = (float)$obj->PriceSL;
    $info->PriceTP = (float)$obj->PriceTP;
    $info->VolumeInitial = (int)$obj->VolumeInitial;
    if (isset($obj->VolumeInitialExt))
      $info->VolumeInitialExt = (int)$obj->VolumeInitialExt;
    else
      $info->VolumeInitialExt = MTUtils::ToNewVolume($info->VolumeInitial);
    $info->VolumeCurrent = (int)$obj->VolumeCurrent;
    if (isset($obj->VolumeCurrentExt))
       $info->VolumeCurrentExt = (int)$obj->VolumeCurrentExt;
    else
       $info->VolumeCurrentExt = MTUtils::ToNewVolume($info->VolumeCurrent);
    $info->ExpertID = (float)$obj->ExpertID;
    $info->ExpertPositionID = (float)$obj->PositionID;
    $info->PositionByID = (float)$obj->PositionByID;
    $info->Comment = (string)$obj->Comment;
    $info->ActivationMode = (int)$obj->ActivationMode;
    $info->ActivationTime = (int)$obj->ActivationTime;
    $info->ActivationPrice = (float)$obj->ActivationPrice;
    $info->ActivationFlags = (int)$obj->ActivationFlags;
    //---
    return $info;
    }
  }

  class MTOrder
  {
  //--- order ticket
  public $Order;
  //--- order ticket in external system (exchange, ECN, etc)
  public $ExternalID;
  //--- client login
  public $Login;
  //--- processed dealer login (0-means auto)
  public $Dealer;
  //--- order symbol
  public $Symbol;
  //--- price digits
  public $Digits;
  //--- currency digits
  public $DigitsCurrency;
  //--- contract size
  public $ContractSize;
  //--- MTEnOrderState
  public $State;
  //--- MTEnOrderReason
  public $Reason;
  //--- order setup time
  public $TimeSetup;
  //--- order expiration
  public $TimeExpiration;
  //--- order filling/cancel time
  public $TimeDone;
  //--- order setup time in msc since 1970.01.01
  public $TimeSetupMsc;
  //--- order filling/cancel time in msc since 1970.01.01
  public $TimeDoneMsc;
  //--- modification flags (type is MTEnOrderTradeModifyFlags)
  public $ModifyFlags;
  //--- MTEnOrderType
  public $Type;
  //--- MTEnOrderFilling
  public $TypeFill;
  //--- MTEnOrderTime
  public $TypeTime;
  //--- order price
  public $PriceOrder;
  //--- order trigger price (stop-limit price)
  public $PriceTrigger;
  //--- order current price
  public $PriceCurrent;
  //--- order SL
  public $PriceSL;
  //--- order TP
  public $PriceTP;
  //--- order initial volume
  public $VolumeInitial;
  //--- order initial volume
  public $VolumeInitialExt;
  //--- order current volume
  public $VolumeCurrent;
  //--- order current volume
  public $VolumeCurrentExt;
  //--- expert id (filled by expert advisor)
  public $ExpertID;
  //--- expert position id (filled by expert advisor)
  public $ExpertPositionID;
  //--- position by id
  public $PositionByID;
  //--- order comment
  public $Comment;
  //--- order activation state (type is MTEnOrderActivation)
  public $ActivationMode;
  //--- order activation time
  public $ActivationTime;
  //--- order activation  price
  public $ActivationPrice;
  //--- order activation flag (type is MTEnTradeActivationFlags)
  public $ActivationFlags;
  }
  
/**
 * Answer on request order_get_total
 */