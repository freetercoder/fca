<?php
class FApiCall{
    public static function callAPI($method, $url, $data=[], $is_json_request = true, $http_headers=[]){        
        $curl = curl_init();

        if (fstr($url)->starts_with("https://")){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
        }


        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data){
                if ($is_json_request){
                    $data = json_encode($data);
                }
                
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              }
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data){
                if ($is_json_request){
                    $data = json_encode($data);
                }
                
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              }
              break;
           default:
              if ($data){
                $url = sprintf("%s?%s", $url, http_build_query($data));
              }
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($is_json_request){
            $http_headers["Accept"] = "application/json";
            $http_headers["Content-Type"] = "application/json";
        }
        
        $request_http_header = [];        
        foreach($http_headers as $k => $v){
            array_push($request_http_header, "$k: $v");
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_http_header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){
            return null;
        }

        curl_close($curl);
        return $result;
     }
}