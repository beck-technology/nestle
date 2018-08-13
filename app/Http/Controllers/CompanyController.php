<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get companies
        // $companies = Company::with(['account' => function($q) use($params){
        //     if (isset($params['account_name'])) {
        //         $q->where('accounts.name', 'like', "%". $params['account_name'] ."%");
        //     }
        // }])->orderBy($request->sort ?? "id", $request->order ?? "DESC")
        //     ->offset($request->offset ?? 0)
        //     ->limit($request->limit ?? 1000)->get();
        $companies = Company::filter($params)->with(['account'])->orderBy($request->sort ?? "id", $request->order ?? "DESC")
            ->offset($request->offset ?? 0)
            ->limit($request->limit ?? 1000)->get();
        $totalItems = Company::filter($params)->count();

        // Return collection of companies as a resource
        $companiesCollection = CompanyResource::collection($companies);
        $dataResponse = new \stdClass();
        $dataResponse->companies = $companiesCollection;
        $dataResponse->total = $totalItems;
        return response()->json($dataResponse);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = $request->isMethod('put') ? Company::findOrFail($request->id) : new Company;
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
        $company->is_parent = $request->input('is_parent');
        $company->is_enable = $request->input('is_enable');
        $company->created_by = $request->input('created_by');
        $company->updated_by = $request->input('updated_by');
        if($company->save()) {
            return new CompanyResource($company);
        }
    }
    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get companies
        $company = Company::findOrFail($id);

        // Return single companies as a resource
        return new CompanyResource($company);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get companies
        $company = Company::findOrFail($id);

        if($company->delete()) {
            return new CompanyResource($company);
        }    
    }
}
