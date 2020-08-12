<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function allPosts(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'username',
            4 => 'phone',
            5 => 'dob',
        );

        $totalData = Student::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $students = Student::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $students = Student::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('dob', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Student::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('dob', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($students)) {
            foreach ($students as $student) {

                /*================================================
                 *              Route Action
                 * ===============================================*/
                // $show =  route('',$ID);
                // $edit =  route('',$ID);

                $nestedData['id'] = $student->id;
                $nestedData['no'] = $start = $start + 1;
                $nestedData['name'] = $student->name;
                $nestedData['email'] = $student->email;
                $nestedData['username'] = $student->username;
//                $nestedData['phone'] = substr(strip_tags($student->phone), 0, 8) . "...";
                $nestedData['phone'] = $student->phone;
                $nestedData['dob'] = date('d/M/Y', strtotime($student->dob));

                /*=====================================================
                 *            Button Action
                 * =====================================================*/
                //$nestedData['action'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                // &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";

                $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                $nestedData['action'] = $btn;
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);

    }


}
