<?php
/**
 * Created by PhpStorm.
 * User: TungPA
 * Date: 8/4/2018
 * Time: 10:02 AM
 */

namespace App\Http\Controllers;


use App\Account;

class AccountsController extends Controller
{
	public function index()
	{
		$accounts = Account::get();

		return response()->json($accounts);
	}
}