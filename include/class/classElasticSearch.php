<?php
class classElasticSearch
{
    private $server;
    private $index;
    private $queryParams = array();
    private $data_json;

    public $hitsCount;
    public $hitsTotal;
    public $curl_info;

    function __construct($elasticConfig = array(),$index){
        $this->server = $elasticConfig["es_url"];
        $this->index = $index;
        $this->queryParams["_source"] = "*._id";
        $this->queryParams["min_score"] = $elasticConfig["search_results_minimum_score_value"];
        $this->queryParams["query"] = array();
        $this->queryParams["query"]["dis_max"] = array();
        $this->queryParams["query"]["dis_max"]["queries"] = array();
    }

    function call($path, $data_json = array()){
        if (!$this->index) throw new Exception('$this->index needs a value');
        $url = $this->server . '/' . $this->index . '/' . $path;

        $method = $data_json['method'];
        $content = $data_json['content'];

        $this->data_json = json_encode($content);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_json = curl_exec($ch);
        $this->curl_info = curl_getinfo($ch);
        curl_close($ch);
        $result = json_decode($result_json, true);
        $this->hitsCount = count($result["hits"]["hits"]);
        $this->hitsTotal = $result["hits"]["total"];
        return $result;
    }

    function setQueryParamsDefault($sQuery){
        $query = array();
        $query["query_string"]["query"] = $sQuery;
        $query["query_string"]["fields"] = array("productname.productname_original^1.5","sku","upc","brand.brand_original^0.5","description.description_original");
        $this->queryParams["query"]["dis_max"]["queries"][] = $query;
        $query = array();
        $query["query_string"]["query"] = $sQuery;
        $query["query_string"]["fields"] = array("productname.productname","sku","upc","brand.brand","description.description");
        $query["query_string"]["fields"] = array("productname.productname^1.5","sku","upc","brand.brand^0.5","description.description");
        $query["query_string"]["analyzer"] = "snowball";
        $this->queryParams["query"]["dis_max"]["queries"][] = $query;
        $query = array();
        $query["match_phrase_prefix"]["sku_original"] = $sQuery;
        $this->queryParams["query"]["dis_max"]["queries"][] = $query;
    }

    function setQueryParams($sQuery, $aDismax = array()){
        if (empty($aDismax)) {
            $this->setQueryParamsDefault($sQuery);
        } else {
            foreach ($aDismax as $oDismax) {
                $this->queryParams["query"]["dis_max"]["queries"][] = $oDismax;
            }
        }
    }

    function setMinScore($sMinScore){
        $this->queryParams["min_score"] = $sMinScore;
    }

    function setFilterTerms($aFilterTerm){
        $this->queryParams["filter"]["terms"]["_id"] = $aFilterTerm;
    }

    //curl -X PUT http://localhost:9200/{INDEX}/
    function create(){
        $this->call(NULL, array('method' => 'PUT'));
    }
    //curl -X DELETE http://localhost:9200/{INDEX}/
    function drop(){
        $this->call(NULL, array('method' => 'DELETE'));
    }
    //curl -X GET http://localhost:9200/{INDEX}/_status
    function status(){
        return $this->call('_status');
    }
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
    function count($type){
        return $this->call($type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
    }
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
    function map($type, $data){
        return $this->call($type . '/_mapping', array('method' => 'PUT', 'content' => $data));
    }
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
    function add($type, $id, $data){
        return $this->call($type . '/' . $id, array('method' => 'PUT', 'content' => $data));
    }
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
    function query($type, $q){
        return $this->call($type . '/_search?' . http_build_query($q), array('method' => 'POST', 'content' => $this->queryParams));
    }
}