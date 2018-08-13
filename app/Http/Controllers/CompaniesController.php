<?php
/**
 * Created by PhpStorm.
 * User: TungPA
 * Date: 8/4/2018
 * Time: 07:23 AM
 */

namespace App\Http\Controllers;

use App\Account;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use App\Filters\GeneralFilter;


class CompaniesController extends  Controller
{
	public function index(Request $request, GeneralFilter $filters)
	{
		$companies = Company::filter($filters)->with('account')->orderBy($request->sort ?? "id", $request->order ?? "DESC")
			->offset($request->offset ?? 0)
			->limit($request->limit ?? 1000)->get();

		return response()->json($companies);
	}

	public function total(Request $request, GeneralFilter $filters) {
		$totalItems = Company::filter($filters)->count();
		echo $totalItems; die;
		return response()->json($totalItems);
	}

	public function store(Request $request)
	{
		$company = $request->isMethod('put') ? Company::findOrFail($request->input('id')) : new Company;
		if ($request->hasFile('image'))
		{
			$image = $request->file('image');
			$imageName = sha1($image->getFilename() . time()) . '.' . $image->getClientOriginalExtension();
			$destinationPath = public_path('/img');
			$image->move($destinationPath, $imageName);
			$company->image = $imageName;
		}
		$company->id = $request->input('id');
		$company->account_id = $request->input('account_id');
		$company->name = $request->input('name');
		$company->website = $request->input('website');
		$company->address = $request->input('address');
		$company->post_code = $request->input('post_code');
		$company->type = $request->input('type');
		$company->phone_number = $request->input('phone_number');
		if($company->save()) {
			return new CompanyResource($company);
		}
	}

	public function show($id)
	{
		$company = Company::findOrFail($id);
		return new CompanyResource($company);
	}

	public function destroy(Request $request, $id)
	{
		$ids = [];

		foreach ($request->all() as $item)
		{
			$ids[] = $item['id'];
		}

		Company::whereIn('id', $ids)->delete();

		return response()->json([]);
	}

	public function getDistributors()
	{
		$companies = Company::where('type', 'Distributor')->get();
		return response()->json(['Companies' => $companies]);
	}
}