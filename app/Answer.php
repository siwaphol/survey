<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = [];

    public static $summaryTable1 = array('no_ra14'=>array(6,7));
    public static $summaryTable2 = array('no_ra14'=>array(6,7));

    public static function parentIsSelectedOrNoParent($splitUniqueKey=[], $input)
    {
        $parentPos = 2;
        if ($splitUniqueKey[count($splitUniqueKey)-1][0]==='o'){
            $parentPos = 3;
        }
        $inputTextAndNumber = ['nu', 'te'];
        // no_ch1_o2_te3 (0,1,2,3)
        for ($i = count($splitUniqueKey) - $parentPos; $i>=0; $i--){
            if ($splitUniqueKey[$i] === 'no'){
                return true;
            }

            if ($splitUniqueKey[$i][0]==='o'){
                //checkbox parent
                if (str_contains($splitUniqueKey[$i-1],'ch')){
                    $temp = array_slice($splitUniqueKey, 0, $i+1);
                    if (isset($input[implode("_",$temp)]) && ($input[implode("_",$temp)]===true || $input[implode("_",$temp)]==='true')){
                        $i--;
                        continue;
                    }
                    return false;
                }elseif (str_contains($splitUniqueKey[$i-1],'ra')){
                    $temp = array_slice($splitUniqueKey, 0, $i);
                    if (isset($input[implode("_",$temp)]) && ($input[implode("_",$temp)]===str_replace('o','',$splitUniqueKey[$i]))){
                        $i--;
                        continue;
                    }
                    return false;
                }
            }elseif (in_array(substr($splitUniqueKey[$i], 0,2),$inputTextAndNumber)){
                $temp = array_slice($splitUniqueKey, 0, $i+1);
                if (isset($input[implode("_",$temp)]))
                    continue;
                return false;
            }
        }

        return false;
    }
}
