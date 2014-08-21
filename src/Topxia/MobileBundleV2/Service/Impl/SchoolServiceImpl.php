<?php


namespace Topxia\MobileBundleV2\Service\Impl;
use Topxia\MobileBundleV2\Service\BaseService;
use Topxia\MobileBundleV2\Service\SchoolService;

class SchoolServiceImpl extends BaseService implements SchoolService {

    public function getUserterms()
    {
        $setting = $this->controller->getSettingService()->get('auth', array());
        if (array_key_exists("user_terms_body", $setting)) {
            $userTerms = $setting['user_terms_body'];
        }
        return array(
            'userTerms' => $userTerms
            );
    }

    public function getWeekRecommendCourses()
    {
        $sort = "recommend";
        $courses = $this->controller->getCourseService()->searchCourses(array(), $sort, 0,  3);
        $result = array(
            "start"=>"0",
            "limit"=>"3",
            "data"=>$this->controller->filterCourses($courses));
        return $result;
    }

    public function getRecommendCourses()
    {
        $sort = "latest";
        $courses = $this->controller->getCourseService()->searchCourses(array(), $sort, 0,  2);
        $result = array(
            "start"=>"0",
            "limit"=>"2",
            "data"=>$this->controller->filterCourses($courses));
        return $result;
    }

    public function getSchoolAnnouncement()
    {
        return array(
            "info"=>"这是网校简介",
            "action"=>"none",
            "params"=>array()
            );
    }

    public function getSchoolBanner()
    {
        return $this->getSchoolBannerFromDb();
    }

    public function getSchoolSiteByQrCode()
    {
        $mobile = $this->controller->getSettingService()->get('mobile', array());
        if (empty($mobile['enabled'])) {
            return $this->createErrorResponse('client_closed', '没有搜索到该网校！');
        }

        $token = $this->controller->getUserToken($request);
        if (empty($token) or  $token['type'] != self::TOKEN_TYPE) {
            $token = null;
        }

        if (empty($token)) {
            $user = null;
        } else {
            $user = $this->controller->getUserService()->getUser($token['userId']);
        }

        $site = $this->controller->getSettingService()->get('site', array());

        $result = array(
            'token' => empty($token) ? '' : $token['token'],
            'user' => empty($user) ? null : $this->filterUser($user),
            'site' => $this->getSiteInfo($request)
        );
        
        return $result;
    }

    public function getSchoolSite() {
        $mobile = $this->controller->getSettingService()->get('mobile', array());
        if (empty($mobile['enabled'])) {
            return $this->createErrorResponse('client_closed', '没有搜索到该网校！');
        }

        $site = $this->controller->getSettingService()->get('site', array());
        $result = array(
            'site' => $this->getSiteInfo($this->controller->request)
        );
        
        return $result;
    }

    private function getSchoolAnnouncementFromDb()
    {
        $result = array();
    }

    private function getSchoolBannerFromDb()
    {
        $banner = array(
            array(
                "url"=>"",
                "action"=>"none",
                "params"=>array()
                ),
            array(
                "url"=>"",
                "action"=>"none",
                "params"=>array()
                ),
            array(
                "url"=>"",
                "action"=>"none",
                "params"=>array()
                )
        );
        return $banner;
    }
}
