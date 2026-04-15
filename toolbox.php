<?php
/**
 * 
 */
// тут делим условия и все через and (условие1 and условие2 and .....)
//я не ебууу кааааак какать(((((((((((((
function conditionConstructor($conditions){
    foreach($conditions as $condition){
        $condition = implode(' ' , $condition);
    }
    return implode(' AND ' , $conditions);
}