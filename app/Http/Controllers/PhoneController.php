<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhoneModel;



class PhoneController extends Controller {
   
	private $model;
	public function __construct(){
		$this->model = new PhoneModel();
	}


	//callable
	public function index(Request $request) {
		return view("phone", ['numbers' => $this->model->chars]);
	}

	//callable
	public function search(Request $request) {
		$result = ['status' => 1];
		try{
			$showAll = (bool)$request->showAll??false;
			[$result['length'], $result['data']] = $this->model->searchUser((string)$request->searchString, $showAll);
		}catch(\Exception $ex){
			$result['status'] = 0;
			$result['message'] = $ex->getMessage().' -> '.$ex->getLine();
		}finally{
			return response()->json($result);
		}
	}

}
