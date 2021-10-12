<?php

defined('_JEXEC') or die;

class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
    }

    public function getSearchResultAction($params) {
        $search_supplier_id = nsoftConfig::$searchSupplierId;

        $categoryId = isset($params['category_search']) ? $params['category_search'] : null;
        $keyWord = isset($params['keyword_search']) ? $params['keyword_search'] : null;
        $jobLocation = isset($params['jobLocation_search']) ? $params['jobLocation_search'] : null;
        $scopId = isset($params['jobArea_search']) ? $params['jobArea_search'] : null;
        //  $customerId = isset($params['customerId'])?$params['customerId']: null;
        $lastId = isset($params['lastId']) ? $params['lastId'] : null;
        $countPerPage = isset($params['countPerPage']) ? $params['countPerPage'] : null;

        $rankId = "";
        $customerId = "";
        $categoryClass = new stdClass();
        $categoryClass->cat = $categoryId;
        $categoryClass->isSub = false;
        $listData = $this->jobsSearch_hunter($categoryClass, $keyWord, $rankId, $scopId, $customerId, $lastId * ($countPerPage - 1), $countPerPage, $search_supplier_id, "", $jobLocation);

        $result = ["jobs" => $listData->result,"TotalHits"=>$listData->TotalHits];
        echo json_encode($result);
        die;
    }

    public function getSearchOptionsAction() {

    //    $employment_types = $this->getEmploymenTypes(nsoftConfig::$employmentTypeSupplierId);
      //  var_dump($employment_types);
       
         // var_dump($employment_types);HunterListItem
          $employment_types = $this->getJobArea()->HunterListItem;
      
        
        $listData['jobArea'] = [];

        if (is_array($employment_types)) {

            foreach ($employment_types as $value) {
                if((int)$value->Value !== 3) //Do not show the ALL option
                    $listData['jobArea'][] = ["name" => $value->Text,
                                            "id" => $value->Value];
            }
        } else if ($employment_types!=null && property_exists($employment_types, "Text")) {
            if((int)$employment_types->Value !== 3) //Do not show the ALL option
                $listData['jobArea'] = [["name" => $employment_types->Text,
                                        "id" => $employment_types->Value]];
        }

       // var_dump($listData['jobArea']);die;


        $listData['categoryList'] = $this->getCategoryList();
        $listData['jobLocations'] = $this->getJobLocationList();

        echo json_encode($listData);
        die;
    }

//    public function getApplyOptionsAction() {
//        //  $employment_types = $this->getEmploymenTypes('a6b6cb37-071f-48f4-81ca-c3c3f44323ea');
//        // var_dump($employment_types);
//        /* $listData['jobArea'] = [["name" => $employment_types->Value,
//          "id" => $employment_types->Value]];
//         */
//
//        $file_list = $this->getFileList($this->user_id, 0, 1000);
//        if (property_exists($file_list->FilesListGetResult, "FileInfo")) {
//            if (is_array($file_list->FilesListGetResult->FileInfo)) {
//                $listData['fileList'] = $file_list->FilesListGetResult->FileInfo;
//            } else {
//                $listData['fileList'] = [$file_list->FilesListGetResult->FileInfo];
//            }
//        } else {
//            $listData['fileList'] = [];
//        }
//
//        $listData['cvList'] = $this->getCVList($this->user_id)['files'];
//
//        echo json_encode($listData);
//        die;
//    }

    public function getJobDescription($params) {

        $listData = $this->jobGetById($params['jobId']);

        echo json_encode($listData);
        die;
    }

}
