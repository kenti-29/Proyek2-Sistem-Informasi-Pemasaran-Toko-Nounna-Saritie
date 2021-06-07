<?php
error_reporting(0);
function buat_akun($email, $pass)
{
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:2407/api/create");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('email' => $email,'password' => $pass)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
    
}

function cek_verifikasi()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:2407/api/cekverify");
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

function sendverifikasi()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:2407/api/sendverify");
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    signout();
    return json_decode($res, true);
}

function signout()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:2407/api/signout");
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

function signin($email, $pass)
{
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:2407/api/signin");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('email' => $email,'password' => $pass)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    signout();
    return json_decode($res, true);
    
}