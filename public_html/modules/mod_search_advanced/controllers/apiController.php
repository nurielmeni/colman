<?php

defined('_JEXEC') or die;

class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
    }

    public function getSearchResultAction($params) {
        $search_supplier_id = nsoftConfig::$advancedSearchSupplierId;

        $category_id = isset($params["categorySearchAdvanced"]) ? $params["categorySearchAdvanced"] : "";
        $JobScop = isset($params["jobTypeSearchAdvanced"]) ? $params["jobTypeSearchAdvanced"] : "";
        $employment_type = isset($params["employeeTypeSearchAdvance"]) ? $params["employeeTypeSearchAdvance"] : "";

        $expertise = isset($params["expertiseSearchAdvanced"]) ? $params["expertiseSearchAdvanced"] : "";
        $subCategory_id = isset($params["subCategorySearchAdvanced"]) ? $params["subCategorySearchAdvanced"] : "";

        $company_id = isset($params["companySearchAdvanced"]) ? $params["companySearchAdvanced"] : "";
        $jobLocation = isset($params["jobLocationSearchAdvanced"]) ? $params["jobLocationSearchAdvanced"] : "";
        $jobUpdateDate = isset($params["jobUpdateDateSearchAdvanced"]) ? $params["jobUpdateDateSearchAdvanced"] : "";
        $jobUpdateDateInterval = $this->getDateInterval($jobUpdateDate);

        $lastId = isset($params['lastId']) ? $params['lastId'] : null;
        $countPerPage = isset($params['countPerPage']) ? $params['countPerPage'] : null;
        $keywordsTypes = '';
        if (isset($params['keyWordsAdvanced']) && isset($params['searchAdvancedType']) && !empty($params['keyWordsAdvanced']) && !empty($params['searchAdvancedType'])) {
            $keywordsTypes = array($params['keyWordsAdvanced'], $params['searchAdvancedType']);
        }
        $categoryClass = new stdClass();

        if (!empty($subCategory_id)) {
            // $category_id = $subCategory_id;
            $categoryClass->sub = $subCategory_id;
            $categoryClass->isSub = true;
        } else {
            $categoryClass->isSub = false;
        }


        $categoryClass->cat = $category_id;
        // var_dump($categoryClass);die;
        $sendToAgent = isset($params["sendToAgentSearchAdvanced"]) ? true : false;

        $listData = $this->jobsSearch_hunter($categoryClass, $keywordsTypes, $expertise, $employment_type, $company_id, $lastId * ($countPerPage - 1), $countPerPage, $search_supplier_id, $JobScop, $jobLocation, $jobUpdateDateInterval, $sendToAgent);

        $result = ["jobs" => $listData->result, "TotalHits" => $listData->TotalHits];
        echo json_encode($result);
        die;
    }

    public function getDateInterval($type) {
        $end = time();
        switch ($type) {
            case '1':
                $start = strtotime("-1 day");
                $interval = date("m/d/Y H:i", $start) . " - " . date("m/d/Y H:i", $end);
                break;

            case '2':
                $start = strtotime("-1 week");
                $interval = date("m/d/Y H:i", $start) . " - " . date("m/d/Y H:i", $end);
                break;

            case '3':
                $start = strtotime("-31 days");
                $interval = date("m/d/Y H:i", $start) . " - " . date("m/d/Y H:i", $end);
                break;

            default:
                $interval = "";
                break;
        }

        return $interval;
    }

    public function getSearchOptionsAction() {
        $supplier_id = nsoftConfig::$employmentTypeSupplierId;

//        $employment_types = $this->getEmploymenTypes($supplier_id);
//        // var_dump($employment_types);
//        if (sizeof($employment_types) > 0) {
//            $listData['jobArea'] = [["name" => $employment_types->Value,
//            "id" => $employment_types->Value]];
//        } else {
//            $listData['jobArea'] = "";
//        }

        $listData['categoryList'] = $this->getCategoryList();

        $listData['jobLocationList'] = $this->getJobLocationList();
        //    $listData['companyList'] = $this->getCompanyList();
        $listData['companyList'] = [];
//        $listData['jobUpdateDateList'] = $this->getJobUpdateDateList();
        $listData['jobTypeList'] = $this->getJobTypeList();
//        $listData['expertiseList'] = $this->getExpertiseList($supplier_id);


        echo json_encode($listData);
        die;
    }

    public function getSubCategoryOptionsAction($params) {

        $listData['categoryList'] = $this->getCategoryList($params['categoryId']);

        echo json_encode($listData);
        die;
    }

    public function getJobDescription($params) {
        $listData = $this->jobGetById($params['jobId']);

        echo json_encode($listData);
        die;
    }

}
