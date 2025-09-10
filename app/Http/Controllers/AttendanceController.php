<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Imports\AttendancesImport;
use App\Models\Attendance_permit;
use App\Models\Recovery_date;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;
use App\Imports\SheetImport;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances  = Attendance::all();
        return response()->json($attendances);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created attendance record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attendancesJson = json_decode($request->get('attendances'), true);
        $attendances = $attendancesJson['data'];

        foreach ($attendances as $attendance) {

            if ($attendance['weekday'] == 'Lunes') {
                $newWeekDay = 1;
            } else if ($attendance['weekday'] == 'Martes') {
                $newWeekDay = 2;
            } else if ($attendance['weekday'] == 'Miércoles') {
                $newWeekDay = 3;
            } else if ($attendance['weekday'] == 'Jueves') {
                $newWeekDay = 4;
            } else if ($attendance['weekday'] == 'Viernes') {
                $newWeekDay = 5;
            } else if ($attendance['weekday'] == 'Sábado') {
                $newWeekDay = 6;
            }

            $newAttendance = Attendance::create([
                'user_id' => $attendance['emp_code'],
                'date' => $attendance['att_date'],
                'first_punch' => $attendance['first_punch'],
                'last_punch' => $attendance['last_punch'],
                'weekday' => $newWeekDay
            ]);
        }

        return response()->json([
            'msg' => 'success'
        ]);
    }

    /**
     * Display the attendance permits for a specific user.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendancePermits = Attendance_permit::where('user_id', $id)->where('miss_date', 'like', date('Y-m') . '-%')->get();

        return response()->json(count($attendancePermits));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
    /**
     * Import attendance records from a JSON file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function jsonFile(Request $request)
    {
        // Verificar si se ha enviado un archivo llamado 'file'
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Leer el contenido del archivo
            $contents = $file->get();

            // Decodificar el contenido JSON
            $data = json_decode($contents, true);

            $oldAttendances = Attendance::all();
            $oldAttendances->each->delete();
            $attendances = $data['data'];

            foreach ($attendances as $attendance) {

                $user = User::find($attendance['emp_code']);

                if ($user) {

                    if ($attendance['weekday'] == 'Lunes') {
                        $newWeekDay = 1;
                    } else if ($attendance['weekday'] == 'Martes') {
                        $newWeekDay = 2;
                    } else if ($attendance['weekday'] == 'Miércoles') {
                        $newWeekDay = 3;
                    } else if ($attendance['weekday'] == 'Jueves') {
                        $newWeekDay = 4;
                    } else if ($attendance['weekday'] == 'Viernes') {
                        $newWeekDay = 5;
                    } else if ($attendance['weekday'] == 'Sábado') {
                        $newWeekDay = 6;
                    }

                    $newAttendance = Attendance::create([
                        'user_id' => $attendance['emp_code'],
                        'date' => $attendance['att_date'],
                        'first_punch' => $attendance['first_punch'],
                        'last_punch' => $attendance['last_punch'],
                        'weekday' => $newWeekDay
                    ]);
                }
            }

            return response()->json([
                'msg' => 'success'
            ]);
        }
    }
    /**
     * Import attendance records from an Excel file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function excelFile(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $file = $request->file->move(public_path('files'), $fileName);
        /* $data = Excel::toCollection(new SheetImport, $file);

        return $data; */

        Excel::import(new AttendancesImport, public_path('files/' . $fileName));

        return response()->json(['success' => 'You have successfully upload file.']);
    }
    /**
     * Store attendance permits and related recovery dates.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function attendancePermits(Request $request)
    {
        $attendancePermit = Attendance_permit::create($request->all());

        $recoveries = json_decode($request->get('recoveries'), true);


        foreach ($recoveries as $recovery) {
            $recoveryDate = Recovery_date::create([
                'recovery_date' => $recovery['dateRecovery'],
                'admission_time' => $recovery['recovery_time_admission'],
                'departure_time' => $recovery['recovery_time_departure'],
                'amount_recovered' => 0,
                'status' => 0,
                'recoverable_id' => $attendancePermit->id,
                'recoverable_type' => 'App\\Models\\Attendance_permit'
            ]);
        }

        return response()->json([
            'msg' => 'success'
        ]);
    }
    /**
     * Retrieve attendance permit requests with user and recovery date information.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermissionsRequest()
    {
        $attendancePermits = Attendance_permit::with(['user', 'recovery_dates'])
            ->orderBy('id', 'desc')
            ->where('miss_date', 'like', '%-' . date('m') . '-%')
            ->get();
        return response()->json($attendancePermits);
    }
    /**
     * Accept an attendance permit request.
     *
     * @param int $id Attendance permit ID
     * @param int $status New status
     * @return \Illuminate\Http\Response
     */
    public function acceptPermit($id, $status)
    {
        $attendancePermit = Attendance_permit::find($id);
        $attendancePermit->update([
            'status' => $status
        ]);

        return response()->json($attendancePermit->status);
    }
    /**
     * Reject an attendance permit request.
     *
     * @param int $id Attendance permit ID
     * @return \Illuminate\Http\Response
     */
    public function rejectPermit($id)
    {
        $attendancePermit = Attendance_permit::find($id);
        $attendancePermit->update([
            'status' => 2
        ]);

        return response()->json($attendancePermit->status);
    }
    /**
     * Retrieve chart values for attendance records in the current month.
     *
     * @return void
     */
    public function chartValues()
    {
        $actualMonth = date('Y') . '-' . date('m');
        $attendances = Attendance::where('date', 'like', '%' . $actualMonth)->get();

        //Futuro algoritmo para calcular la sumatoria de tardanzas y faltas, es necesario almacenar, las faltas y tardanzas en una tabla
    }
}
