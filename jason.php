<?php

/**
 * jason is a simple JSON reader/writer for PHP.
 * Copyright (C) 2012-2014 Jublo IT Solutions <support@jublo.net>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Jason
{
    // use PHP execution protection
    protected static $_boolPhpProtection = true;

    public static function setPhpProtection($boolPhpProtection)
    {
        self::$_boolPhpProtection = $boolPhpProtection;
    }

    // decode JSON object
    public static function decode($strData)
    {
        $mixData = json_decode($strData);
        if (self::$_boolPhpProtection)
        {
            // remove existing protection
            if (is_object($mixData) && isset($mixData->___))
            {
                unset($mixData->___);
            }
        }
        return $mixData;
    }

    // encode JSON object
    public static function encode($mixData)
    {
        if (self::$_boolPhpProtection)
        {
            // add protection
            if (is_object($mixData))
            {
                $mixData = json_decode(json_encode($mixData), true);
            }
            if (is_array($mixData) && !isset($mixData[0]))
            {
                $mixData = array_merge(
                    array('___' => '<?php die(); ?>'),
                    $mixData);
            }
        }
        $mixData = json_encode($mixData);
        return $mixData;
    }

    public static function read($strFile)
    {
        // check if file exists
        if (!file_exists($strFile))
        {
            die('Settings file does not exist.');
        }
        $strData = file_get_contents($strFile);
        return self::decode($strData);
    }

    public static function write($strFile, $mixData)
    {
        $resFile = fopen($strFile, 'wb');
        fwrite($resFile, self::encode($mixData));
        fclose($resFile);
        return true;
    }
}
