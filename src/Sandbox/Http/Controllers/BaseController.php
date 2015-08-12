<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 29.07.15
 * Time: 17:29
 * Project: sandbox
 */

namespace Laravelblog\Sandbox\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Laravelblog\Sandbox\Repositories\UserRepository;

class BaseController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var UserRepository
     */
    protected $_oUser;

    protected $_oMe;

    protected $_aCookies = array();

    protected $_sSiteName = null;

    /**
     * @param UserRepository $oUser
     */
    public function __construct(UserRepository $oUser)
    {
        $this->_standardInit($oUser);
    }

    public function view($sViewName, array $aData = [])
    {
        $aData = $this->_prepareViewData($aData);
        $oView = view($sViewName, $aData);
        $oResponse = \Response::make($oView);
        $this->_aCookies = \Helper::getCookies();
        foreach ($this->_aCookies as $oCookie) {
            $oResponse->withCookie($oCookie);
        }
        return $oResponse;
    }

    /**
     * @param UserRepository $oUser
     */
    protected function _initializeMe(UserRepository $oUser)
    {
        $this->_oMe = \Helper::getMe();
        $this->_oUser = $oUser;
    }

    /**
     * @param array $aData
     * @return array
     */
    protected function _prepareViewData(array $aData)
    {
        $aData = array_merge($aData, ['user' => $this->_oUser]);
        if (!isset($aData['q'])) {
            $aData['q'] = \Input::get('q');
        }
        $aData['site_name'] = $this->_sSiteName;
        return $aData;
    }

    /**
     * @param UserRepository $oUser
     */
    protected function _standardInit(UserRepository $oUser)
    {
        $this->_initializeMe($oUser);
        $this->middleware('auth');
    }

}