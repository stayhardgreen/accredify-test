<?php

function getIssuerName(array $array): string
{
    if(
        !empty($array) &&
        !empty($array['data']) &&
        !empty($array['data']['issuer']) &&
        !empty($array['data']['issuer']['name'])
    ){
        return $array['data']['issuer']['name'];
    }

    return '';
}
