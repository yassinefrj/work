<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

/**
 * The ReportController class extends Laravel's base Controller class. 
 * It is responsible for generating and exporting reports related to user tasks.
 */
class ReportController extends Controller
{
    /**
     * The reports method is responsible for rendering the view that displays reports.
     *  It does not perform data export but sets up the presentation of the report view.
     */
    public function reports(Request $request)
    {
        return view('reports.report');
    }

    /**
     * The exportCSV method generates a CSV file containing activity history data for user tasks.
     *  It retrieves data using the Task::getUsersTasksDataForCSV() method, sets up CSV headers, and streams the CSV file as a response.
     */
    public function exportCSV(Request $request)
    {
        $fileName = 'ActivityHistory.csv';

        $data = Task::getUsersTasksDataForCSV();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('identifier', 'user\'s name', 'user\'s email', 'task\'s identifier', 'task\'s name', 'task\'s description', 'task\'s starting time', 'task\'s ending time');

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $d) {
                $row['user_id'] = $d->user_id;
                $row['user_name'] = $d->user_name;
                $row['user_email'] = $d->user_email;
                $row['task_id'] = $d->task_id;
                $row['task_name'] = $d->task_name;
                $row['task_description'] = $d->task_description;
                $row['task_start_datetime'] = $d->task_start_datetime;
                $row['task_end_datetime'] = $d->task_end_datetime;

                fputcsv($file, array($row['user_id'], $row['user_name'], $row['user_email'], $row['task_id'], $row['task_name'], $row['task_description'], $row['task_start_datetime'], $row['task_end_datetime']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
