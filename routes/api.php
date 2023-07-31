<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


// FOR SIMPLICITY WE WON'T IMPLEMENT DELETE OPERATIONS.

/*
 * TODO: Get all students list. ALREADY IMPLEMENTED IN THE DEMO SESSION.
 * URL: GET /students
 * Response:
     Status code: 200
     JSON body: 
         { 
           "data": [    
              { 
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              },
              { 
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              }
           ]
        }
 */
Route::get('/students', function (Request $request) {
    $rawData = DB::select(DB::raw("select id, name, email, phone from students"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'id' => $rd->id,
            'name' => $rd->name,
            'email' => $rd->email,
            'phone' => $rd->phone,
        ]);
    }

    $statusCode = 200;

    return response()->json([  
            'data' => $responseData
    ], $statusCode);
});


/* 
    * TODO: Create new student.
    * Status: Completed
    * URL: POST /students
    * Request Body:
        {   
            "name": "student_name",
            "email": "student_email",
            "phone": "student_phone"
        }
    * Response:
        status_code: 200
        JSON body: 
            {   
                "data": {   
                    "id": "student_id_from_database"
                }
            }
*/
Route::post("/students",function(Request $request) {
    $name = $request->input("name");
    $email = $request->input("email");
    $phone = $request->input("phone");

    $id = DB::table("students")->insertGetId([
        "name" => $name,
        "email" => $email,
        "phone" => $phone
    ]);

    $statusCode = 200;

    return response()->json([
        "data" => [
            "id" => $id
        ]
    ], $statusCode);
});


/* 
    * TODO: Get student details by id
    * Status: Completed
    * URL: GET /students/{id}
    * Response:
       * success:
            status_code: 200
            JSON body: 
                { 
                    "data": {
                        "id": "student_id",
                        "name": "student_name",
                        "email": "student_email",
                        "phone": "student_phone"
                    }
                }
       * not found:
            status_code: 404
            JSON body: 
                {   
                    "data": {}
                }
*/
Route::get("/students/{id}",function(Request $request){
    $id = $request->route("id");
    //get student by if
    $student = DB::table("students")->where("id",$id)->first();
    //check is student exists
    if($student){
        $statusCode = 200;
        $responseData = [
            "id" => $student->id,
            "name" => $student->name,
            "email" => $student->email,
            "phone" => $student->phone
        ];
    }else{
        $statusCode = 404;
        $responseData = [];
    }
    return response()->json([
        "data" => $responseData
    ],$statusCode);
});
/*
    * TODO: Update student data\
    * Status: Completed
    * URL: PUT /students/{id}
    * Request Body:
        {   
            "name": "new_student_name",
            "email": "new_student_email",
            "phone": "new_student_phone"
        }
    * Response:
        status_code: 200
        JSON body:
            {   
                "data": {   
                    "id": "student_id",
                    "name": "new_student_name",
                    "email": "new_student_email",
                    "phone": "new_student_phone"
                }
            }
 */
Route::put("/students/{id}",function(Request $request){
    $id = $request->route("id");
    $student = DB::table("students")->where("id",$id)->first();
    
    if(!$student){
        $statusCode = 404;
        $responseData = ["Error" => "Student not found"];
    }
    else{
        $name = $request->input("name");
        $email = $request->input("email");
        $phone = $request->input("phone");
    
        DB::table("students")->where("id",$id)->update([
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ]);

        $student = DB::table("students")->where("id",$id)->first();
        $statusCode = 200;

        $responseData = [
            "id" => $student->id,
            "name" => $student->name,
            "email" => $student->email,
            "phone" => $student->phone
        ];

    }
    return response()->json([
        "data" => $responseData
    ],$statusCode);

});

 /*
   * TODO: For Courses implement Get, Create & Update endpoints same as students 
   * Get all URL: GET /courses
   * Get course details: GET /courses/{id}
   * Create new course: POST /courses{id}
   * Update course: PUT /courses/{id} 
   * Note: For JSON keys in both the request and response, let's use the same database columns names.
 */

 // GET /courses
 Route::get("/courses",function(){
    $rawData = DB::select(DB::raw("select * from courses"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'id' => $rd->id,
            'name' => $rd->name,
        ]);
    }

    $statusCode = 200;

    return response()->json([  
            'data' => $responseData
    ], $statusCode);
 });

 // GET /courses/{id}
 Route::get("/courses/{id}",function(Request $request){
    $id = $request->route("id");
    $course = DB::table("courses")->where("id",$id)->first();
    $responseData = [
        'id' => $course->id,
        'name' => $course->name
    ];

    $statusCode = 200;

    return response()->json([  
            'data' => $responseData
    ], $statusCode);

 });

 // POST /course
 Route::post("/courses",function(Request $request){
    $name = $request ->input("name");

    $id = DB::table("courses")->insertGetId([
        "name" => $name,
    ]);

    $statusCode = 200;

    return response()->json([
        "data" => [
            "id" => $id
        ]
    ], $statusCode);

 });

 // PUT /courses{id}
 Route::put("/courses/{id}",function(Request $request){
    $id = $request->route("id");
    $course = DB::table("courses")->where("id",$id)->first();
    
    if(!$course){
        $statusCode = 404;
        $responseData = ["Error" => "Course not found"];
    }
    else{
        $name = $request->input("name");
    
        DB::table("courses")->where("id",$id)->update([
            "name" => $name,
        ]);

        $course = DB::table("courses")->where("id",$id)->first();
        $statusCode = 200;

        $responseData = [
            "id" => $course->id,
            "name" => $course->name,
        ];

    }
    return response()->json([
        "data" => $responseData
    ],$statusCode);
 });

 /*
  * TODO: Get all grades endpoint
  * Status : Completed 
  * URL: GET /grades
  * Response:
        status_code: 200
        JSON body: {    
            "data": [   
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */
Route::get("/grades",function(Request $request){
    $rawData = DB::select(DB::raw("select * from  grades"));

    $responseData = [];

    foreach($rawData as $rd){
        array_push($responseData,[
            'student_id' => $rd->student_id, 
            'course_id' => $rd->course_id,
            'grade' => $rd->grade
        ]);
    }

    $statusCode = 200;

    return response()->json([  
            'data' => $responseData
    ], $statusCode);

});
  /*
   * TODO: Get grades for specific student only.
   * Status : Completed
   * URL: GET /students/{student_id}/grades
   * Response:
        status_code: 200
        JSON body: {    
            "data": [   
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */
Route::get("/students/{student_id}/grades",function(Request $request){
    $student_id = $request -> route("student_id");
    $rawData = DB::select("select * from grades where student_id = $student_id");
    $responseData = [];
    
    foreach($rawData as $rd){
        array_push($responseData,[
            'student_id' => $rd->student_id, 
            'course_id' => $rd->course_id,
            'grade' => $rd->grade
        ]);
    }

    $statusCode=200;
    return response()->json([
        "data" => $responseData
    ],$statusCode);
});

  /*
   * TODO: Get specific grade for specific student only. Shall return one record only if exists.
   * Status: Completed
   * URL: GET /students/{student_id}/grades/{grade_id}
   * Response:
        status_code: 200
        JSON body: {    
            "data": {   
                "student_id": "STUDENT ID"
                "course_id": "COURSE ID",
                "grade": "GRADE"
            }
        }
  */

Route::get("/students/{student_id}/grades/{grade_id}",function(Request $request){
    $student_id = $request->route("student_id");
    $grade_id = $request->route("grade_id");

    $rawData = DB::select(DB::raw("select * from grades where student_id = $student_id and id = $grade_id"));
    if(!$rawData){
        $statusCode = 200;
        $responseData = []; 
    }else{
        $statusCode = 200;
        $responseData = [
            "student_id" => $rawData[0]->student_id,
            "course_id" => $rawData[0]->course_id,
            "grade" => $rawData[0]->grade
        ];
    }

    return response()->json([
        "data" => $responseData
    ],$statusCode);
});
