<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 30.06.15
 * Time: 09:37
 * Project: shop
 */

namespace Laravelblog\Sandbox\Lib;


use Carbon\Carbon;
use Laravelblog\Sandbox\Repositories\UserRepository;

class Helper {

    protected $_iCurPrecision = null;

    protected $_oMe;

    protected $_aCookies = [];

    /**
     * @var UserRepository
     */
    protected $_oUserRepository;

    public function __construct(UserRepository $userRepository) {
        $this->_oUserRepository = $userRepository;
    }

    public function allowed($sAction = null,$sRole = null)
    {
        if(static::isAdmin() || \Entrust::hasRole('admin') || \Entrust::can($sAction) || \Entrust::hasRole($sRole)) {
            return true;
        }
        return false;
    }

    public function isAdmin() {
        if($oMe = static::getMe()) {
            return $oMe->isAdmin();
        }
    }

    public function fRound($sVal)
    {
        //cached currency precision, this saves about 1% of execution time
        $iCurPrecision = $this->_iCurPrecision;

        // if < 5.3.x this is a workaround for #36008 bug in php - incorrect round() & number_format() result (R)
        static $dprez = null;
        if (!$dprez) {
            $prez = @ini_get("precision");
            if (!$prez || $prez > 12) {
                $prez = 12;
            }
            $dprez = pow(10, -$prez);
        }

        return round($sVal + $dprez * ($sVal >= 0 ? 1 : -1), $iCurPrecision);
    }

    public function formatPriceWithoutCurrency($mxPrice, $iAfterComma = 2)
    {
        $dValue = $mxPrice;
        if ($mxPrice instanceof Price) {
            $dValue = $mxPrice->getPrice();
        }
        $sRet = number_format($dValue, $iAfterComma, trans('shop.decimal_sign'), trans('shop.thousand_sign'));
        return $sRet;
    }

    public function formatPrice($mxPrice, $iAfterComma = 2, $sCurrency = 'EUR')
    {
        $sRet = $this->formatPriceWithoutCurrency($mxPrice, $iAfterComma);
        $sRet .= ' ' . trans('shop.' . $sCurrency . '_sign');
        return $sRet;
    }

    public function formatDateTime(Carbon $oDateTime)
    {
        return $oDateTime->format(trans('shop.datetime_format'));
    }

    public function formatDate(Carbon $oDateTime)
    {
        return $oDateTime->format(trans('shop.date_format'));
    }

    public function rescueFloatValue($mxFloatValue)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $mxFloatValue)));
    }

    public function addCookie($sName, $mxValue, $iTime = 0) {
        if($iTime > 0) {
            $this->_aCookies[] = \Cookie::make($sName, $mxValue, $iTime);
        } else {
            $this->_aCookies[] = \Cookie::forever($sName,$mxValue);
        }
    }

    public function getMe()
    {
        if ($this->_oMe == null) {
            $this->_oMe = $this->_oUserRepository->me();
        }
        return $this->_oMe;
    }

    public function getCookies()
    {
        return $this->_aCookies;
    }

}