<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class PhoneModel extends Model {
    
	private $searchNumbers, $relationStrings = [];
    public function __construct(){
    	$this->chars = $this->getChars();
    }

    public function searchUserF(string $searchString, bool $onlyCount = true) : ?array {
    	$this->searchNumbers = str_split($searchString);
    	$result = $this->phoneCNT(count($this->searchNumbers));
    	if(!count($result))return null;

    	//$select = DB::table("users")->whereBetween(DB::Raw("concat(firstName)"))


    	return [];

    }

    public function searchUser(string $searchString, bool $showAll = false) : ?array {
    	$this->searchNumbers = str_split($searchString);
    	$result = $this->phoneCNT(count($this->searchNumbers));
    	if(!count($result))return null;
    	$select = DB::table('users')->get();
    	$match = [];
    	$maxCount = 0;
    	$lenght = [];
    	foreach($select as $key => $value){
    		try{
	    		foreach($result as $searchString){
	    			if(in_array($value->id, $lenght))break;
	    			$search = strtolower($searchString);
	    			$firstName = strtolower($value->firstName);
	    			$lastName = strtolower($value->lastName);
	    			if(Str::contains($firstName, $search) || Str::contains($lastName, $search)){
	    				$lenght[] = $value->id;
	    				if(!$showAll && count($match) > 3)continue;
	    				$match[] = $this->getValueAsString($search, $firstName, $lastName, $value->phone);
	    			}
	    		}
    		}catch(\Exception $ex){
    			continue;
    		}
    	}
    	return [count($lenght), $match];
    }

    private function getValueAsString(string $key, string $firstName, string $lastName, string $phone) : array {
    	$result = [];
    	$result['name'] = str_replace($key, '<span class="text-blue-400">'.$key.'</span>', $firstName.' '.$lastName);
    	$result['phone'] = $phone;
    	return $result;
    }

    private function phoneCNT(int $count = 4) : array {
		$maxVal = pow($count, $count) - 1;
		$cnt = 0;
		$result = [];
		$templ = array_map(fn($var) => 0, range(0, $count - 1));
		try{
			for($i = 0; $i <= $maxVal; $i++){
				if($cnt >= $count){
					$cnt = 0;
					foreach(range(-1, -($count + 1), -1) as $j){
						$k_ = (count($templ) + ($j));
						if($templ[$k_] < $count - 1){
							$templ[$k_] += 1;
							break;
						}else
							$templ[$k_] = 0;
					}
				}
				$templ[count($templ) - 1] = $cnt;
				$cnt += 1;
				$string = '';
				for($iter = 0; $iter < $count; $iter++){
					$temp = $this->tempForIter($iter);
					if($templ[$iter] < count($temp))
						$string .= $temp[$templ[$iter]];
				}
				$result[] = $string;
			}
		}catch(\Exception $ex){
			echo $ex->getMessage();
		}
		return $result;
	}

    private function tempForIter(int $iter) : array {
    	$k = $this->searchNumbers[$iter];
    	return $this->chars[$k]; 
    }

    public function pre($param) : void {
    	echo "<pre>";
    	print_r($param);
    	echo "</pre>";
    }

    public function getChars() : array {
		//Cache::delete("getChars");
		$getNumbers = Cache::get("getChars");
		if($getNumbers)
			return $getNumbers;
		$lastUnusedChars = ['*', '0 +', '#'];
		$chars = range('A', 'Z');
		$getNumbers = [];	
		$cursor = 0;
		for($i = 1; $i <= 12; $i++){
			if($i == 1){
				$getNumbers[$i] = ''; 
				continue;
			}
			if($cursor < count($chars)){
				$charsLength = in_array($i, [7, 9])?4:3;
				$getNumbers[$i] = array_slice($chars, $cursor, $charsLength);
				$cursor += $charsLength;
			}else
				$getNumbers[$i] = $lastUnusedChars[$i - (ceil($cursor / 3) + 1)];
		}

		$expiresAt = now()->addDays(1);
		Cache::add('getChars', $getNumbers, $expiresAt);
		//$this->model->pre($getNumbers);
		return $getNumbers;
	}
}
