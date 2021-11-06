<?php

defined('_JEXEC') or die;

class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
    }

    public function getContentAction($params) {

        $supplier_id = nsoftConfig::$categorySupplierId;
              $lastId = isset($params['lastId']) ? $params['lastId'] : null;
        $countPerPage = isset($params['countPerPage']) ? $params['countPerPage'] : null;
        
        
        
        $categoryClass = new stdClass();
        $categoryClass->cat = $params['catId'];
        $categoryClass->isSub = false;
       // $listData1 = $this->jobsSearch($params['catId'], null, null, null, null, $lastId * ($countPerPage - 1), $countPerPage, $supplier_id);
        $listData = $this->jobsSearch_hunter($categoryClass, null, null, null, null, $lastId * ($countPerPage - 1), $countPerPage, $supplier_id);

        $result = ["jobs" => $listData->result,"TotalHits"=>$listData->TotalHits];
//        $listData = ["jobs" => $listData1,
//            "status" => 0];


        echo json_encode($result);
        die;
    }

//    public function getApplyOptionsAction() {
//
//        //  $employment_types = $this->getEmploymenTypes('a6b6cb37-071f-48f4-81ca-c3c3f44323ea');
//        // var_dump($employment_types);
//        /* $listData['jobArea'] = [["name" => $employment_types->Value,
//          "id" => $employment_types->Value]];
//         */
//        $file_list = $this->getFileList($this->user_id, 0, 1000);
//
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
