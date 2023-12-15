<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupParticipation;
use App\Models\User;
use Illuminate\Http\Request;

class GroupParticipationController extends Controller
{

    /**
     * Get all participants in groups (API endpoint).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllParticipantsGroupApi()
    {
        $groupParticipations = GroupParticipation::with('user', 'group')->get();
        return response()->json($groupParticipations);
    }

    /**
     * Get all participants in groups and organize them by group for the view.
     *
     * @return \Illuminate\View\View
     */
    public function getAllParticipantsGroup()
    {
        // Retrieve all group participations with associated users and groups
        $groupParticipations = GroupParticipation::with('user', 'group')->get();
        // Group participations by group_id
        $groupedParticipants = $groupParticipations->groupBy('group_id');
        // Get all group IDs
        $allGroupIds = Group::pluck('id')->toArray();
        // Create a collection to store the final grouped data
        $groupedData = collect();

        foreach ($allGroupIds as $groupId) {
            $group = Group::find($groupId);
            $participants = $groupedParticipants->get($groupId, collect());

            // Add participation status to each participant
            $groupedData->push([
                'group' => $group,
                'participants' => $participants->map(function ($participant) {
                    return [
                        'user' => $participant->user,
                        'status' => $participant->status,
                    ];
                }),
                'waitingCount' => $participants->where('status', 'waiting')->count(),
            ]);
        }
        return response()->json($groupedData);
    }

    public function getWaitingCount()
    {
        // Get all group participations
        $groupParticipations = GroupParticipation::all();

        // Count waiting participations
        $waitingCount = $groupParticipations->where('status', 'waiting')->count();

        return response()->json(['waitingCount' => $waitingCount]);
    }

    /**
     * Register a user to a group.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerToGroup(Request $request)
    {
        // Retrieve user and group IDs from the request body
        $userId = $request->input('userId');
        $groupId = $request->input('groupId');

        // Check if the user is already registered for the group
        $existingParticipation = GroupParticipation::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->first();
        if ($existingParticipation != null) {
            // User is already registered for the group
            return response()->json(['message' => 'User is already registered for the group'], 400);
        }
        // User is not registered for the group, proceed with registration
        $groupParticipation = GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'status' => 'waiting',
        ]);

        if ($groupParticipation) {
            return response()->json(['message' => 'Registration to the group successful']);
        } else {
            return response()->json(['message' => 'Error during group registration'], 500);
        }
    }

    /**
     * Get the groups associated with a user.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsByUserId($userId)
    {
        $groupParticipation = GroupParticipation::where('user_id', $userId)->with('group')->get();
        return response()->json($groupParticipation);
    }

    /**
     * Unregister a user from a group.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unregisterToGroup(Request $request)
    {
        $userId = $request->input('user_id');
        $groupId = $request->input('group_id');
        $existingParticipation = GroupParticipation::where('user_id', $userId)->where('group_id', $groupId)->first();
        if ($existingParticipation != null) {
            $existingParticipation->delete();
            return response()->json(['message' => 'User successfully unregistered from the group']);
        }
        return response()->json(['error' => 'The user does not belong to this group']);
    }

    /**
     * Update the user participation status to "register".
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserParticipation(Request $request)
    {
        $userId = $request->input('user_id');
        $groupId = $request->input('group_id');

        $existingParticipation = GroupParticipation::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->first();
        if ($existingParticipation != null) {
            $existingParticipation->update(['status' => 'register']);
            return response()->json(['message' => 'User accepted successfully !']);
        }
        return response()->json(['error' => 'An error occurred while updating the user participation.'], 500);
    }

    /**
     * Reject the user participation and remove from the group.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectUserParticipation(Request $request)
    {
        $userId = $request->input('user_id');
        $groupId = $request->input('group_id');

        $existingParticipation = GroupParticipation::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->first();
        if ($existingParticipation != null) {
            $existingParticipation->delete();
            return response()->json(['message' => 'User rejected successfully !']);
        }
        return response()->json(['error' => 'An error occurred while rejected the user participation.'], 500);
    }
}
