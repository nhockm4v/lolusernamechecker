<?php

if(isset($_GET['ingame'])){
    echo CheckUsername($_GET['ingame']);
}

function CheckUsername($ingame){
    $urlCheckId =       'https://acs-garena.leagueoflegends.com/v1/players?region=VN&name=';
    $urlCheckUsername = 'http://gameprofile.garenanow.com/api/game_profile/?region=vn&native=1&uid=';

    $getID  = curlGet($urlCheckId . urlencode($ingame));
    $dataID = @json_decode($getID, true);
    if(isset($dataID['accountId'])){
        $id = $dataID['accountId'];
    }else{
        return json_encode(array(
            'error'  => 'Tên nhân vật game không tìm thấy, Vui lòng kiểm tra lại'
        ));
    }
    $getDataAccount = curlGet($urlCheckUsername . $id);
    $dataAccount = @json_decode($getDataAccount, true);
    if(isset($dataAccount['user_profile']) && isset($dataAccount['game_profiles'][0]['game_stats'])){
        return json_encode(array(
            'id'        => $id,
            'username'  => $dataAccount['user_profile']['username'],
            'avatar'    => $dataAccount['user_profile']['avatar'],
            'nickname'  => $dataAccount['user_profile']['nickname'],
            'name'      => isset($dataAccount['game_profiles'][0]['game_stats']['name']) ? $dataAccount['game_profiles'][0]['game_stats']['name'] : null,
            'level'     => isset($dataAccount['game_profiles'][0]['game_stats']['name']) ? $dataAccount['game_profiles'][0]['game_stats']['level'] : null,
            'rank'      => isset($dataAccount['game_profiles'][0]['game_stats']['name']) ? $dataAccount['game_profiles'][0]['game_stats']['rank'] : null
        ));
    }else{
        return json_encode(array(
            'error'  => 'Lấy thông tin tài khoản thất bại'
        ));
    }
}

function curlGet($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return json_encode(array(
            'error'  => curl_error($ch)
        ));
    }
    curl_close ($ch);
    return $result;
}