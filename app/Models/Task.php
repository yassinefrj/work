<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'people_count',
        'start_datetime',
        'end_datetime',
        'address',
        'people_min',
        'people_max',
    ];

    public $timestamps = false;

    public static function allWithParticipations()
    {
        return DB::select("SELECT tasks.id as id, name, description, people_count,
                            start_datetime, end_datetime, address, participations.id_task, people_min, people_max
	                        FROM tasks
	                        left JOIN participations on tasks.id = participations.id_task;");
    }

    /*
    TODO: we're no longer using participations, so I (ayoub) changed it with task_user manually.
    That said, to not be at the whim of whatever join table we're using, we should
    switch to Eloquent.
    */

    public static function allForUser($id)
    {
        return DB::select("SELECT tasks.id as id, name, description, people_count,
                            start_datetime, end_datetime, address, task_user.id_task
	                        FROM tasks
	                        left JOIN task_user on tasks.id = task_user.id_task
                            where task_user.id_user = ?;", [$id]);
    }

    /**
     * The users that belong to the task.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'id_task', 'id_user');
    }

    public static function getAllTasksForUser($id_user)
    {
        return self::select('tasks.*', 'task_user.id_task', 'task_user.id_user',
            DB::raw("CASE 
                        WHEN task_user.id_user IS NOT NULL THEN 'Inscrit'
                        ELSE 'Non Inscrit'
                        END as StatusInscription"))
            ->leftJoin('task_user', function ($join) use ($id_user) {
                $join->on('tasks.id', '=', 'task_user.id_task')
                    ->where('task_user.id_user', '=', $id_user);
            });
    }

    public static function sortTasksBy()
    {

    }

    public static function register($task_id, $user_id)
    {
        $user = User::find($user_id);
        $task = self::find($task_id);
        $user->tasks()->attach($task->id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    public static function unregister($task_id, $user_id)
    {
        $user = User::find($user_id);
        $task = self::find($task_id);
        $user->tasks()->detach($task->id);
    }

    public static function incrementPeopleCount($taskId)
    {
        $task = self::find($taskId);
        if ($task) {
            if ($task->people_count < $task->people_max) {
                $task->people_count += 1;
                $task->save();
                return $task->people_count;
            }
        }
    }
    public static function decrementPeopleCount($taskId)
    {
        $task = self::find($taskId);
        if ($task) {
            if ($task->people_count > 0) {
                $task->people_count -= 1;
                $task->save();
                return $task->people_count;
            }
        }
    }

    public static function check_nb_participation($task_id)
    {
        $task = self::find($task_id);
        if ($task) {
            return $task->people_count < $task->people_min;
        } else {
            return false;
        }
    }

    public static function getDetailParticipation($idTask)
    {
        $participations = DB::table('participations')
            ->join('users', 'participations.id_user', '=', 'users.id')
            ->select('users.name', 'users.email')
            ->where('id_task', '=', $idTask)
            ->get();

        return $participations;
    }
}