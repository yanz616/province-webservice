<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProvinceModel;

use App\Helpers\ApiFormatter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $province = ProvinceModel::orderBy('province_id', 'ASC')->get();

        $response = ApiFormatter::createJson(200, 'Get Succes', $province);
        return response()->json($response);
    }

    public function create(Request $request,)
    {
        try {
            $params = $request->all();

            $validator = Validator::make(
                $params,
                [
                    'code' => 'required|max:10',
                    'name' => 'required',
                ],
                [
                    'code.required' => 'Province Code is Required',
                    'code.max' => 'Province Code Must not exceed 10 characters',
                    'name.required' => 'Province Name is required',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $province = [
                'province_code' => $params['code'],
                'province_name' => $params['name'],
            ];

            $data = ProvinceModel::create($province);
            $createdProvince = ProvinceModel::find($data->province_id);

            $response = ApiFormatter::createJson(200, 'create Succes', $createdProvince);
            return response()->json($response);
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Eror', $e->getMessage());
            return response()->json($response);
        }
    }
    public function details($id)
    {
        try {
            $province = ProvinceModel::find($id);

            if (is_null($province)) {
                return ApiFormatter::createJson(404, 'Province Not Found');
            }

            $response = ApiFormatter::createJson(200, 'Get Detail Succes', $province);
            return response()->json($response);
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, $e->getMessage());
            return response()->json($response);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();

            $preProvince = ProvinceModel::find($id);
            if (is_null($preProvince)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $validator = Validator::make(
                $params,
                [
                    'code' => 'required|max:10',
                    'name' => 'required',
                ],
                [
                    'code.required' => 'Province Code is Required',
                    'code.max' => 'Province Code Must not exceed 10 characters',
                    'name.required' => 'Province Name is required',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $province = [
                'province_code' => $params['code'],
                'province_name' => $params['name'],
            ];

            $preProvince->update($province);
            $updatedProvince = $preProvince->fresh();

            $response = ApiFormatter::createJson(200, 'Updated Succes', $updatedProvince);
            return response()->json($response);
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Eror', $e->getMessage());
            return response()->json($response);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();

            $preProvince = ProvinceModel::find($id);
            if (is_null($preProvince)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            if (isset($params['code'])) {
                $validator = Validator::make(
                    $params,
                    [
                        'code' => 'required|max:10',
                    ],
                    [
                        'code.required' => 'Province Code is Required',
                        'code.max' => 'Province Code Must not exceed 10 characters',
                    ]
                );

                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request');
                    return response()->json($response);
                }

                $province['province_code'] = $params['code'];
            }

            if (isset($params['name'])) {
                $validator = Validator::make(
                    $params,
                    [
                        'name' => 'required'
                    ],
                    [
                        'nama.required' => 'province_name is required',
                    ]
                );

                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request');
                    return response()->json($response);
                }
                $province['province_name'] = $params['name'];
            }

            $preProvince->update($province);
            $updatedProvince = $preProvince->fresh();

            $response = ApiFormatter::createJson(200,'Update Succes',$updatedProvince);
            return response()->json($response);
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500,'Internal Server Eror',$e->getMessage());
            return response()->json($response);
        }
    }

    public function delete($id){
        try {
            $province = ProvinceModel::find($id);
            if(is_null($province)){
                return ApiFormatter::createJson(400,'Data Not Found');
            }

            $province->delete();

            $response = ApiFormatter::createJson(200,'Delete Succes');
            return response()->json($response);
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500,'Internal Server Eror',$e->getMessage());
            return response()->json($response);
        }
    }
}
