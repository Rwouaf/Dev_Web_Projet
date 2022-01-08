<?php

class dump
{
    public static function changeDate($date): string
    {
        return substr($date,6,9).substr($date,2,3).substr($date,-5,-4).substr($date,0,2);
    }
}