<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\GroupParticipation;

class GroupParticipationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test function to verify the response structure of the getAllParticipantsGroupApi endpoint.
     *
     * This test ensures that the getAllParticipantsGroupApi endpoint returns a response
     * with a specific JSON structure containing information about participants in groups.
     */
    public function testGetAllParticipantsGroupApi()
    {
        // Call the API method
        $response = $this->get('/api/groupspart');

        // Assert the response status code is 200 (OK)
        $response->assertStatus(200);

        // Verify the JSON structure of the response
        $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'updated_at',
                'user_id',
                'group_id',
                'validated',
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'google_id',
                    'microsoft_id',
                    'isAdmin',
                ],
                'group' => [
                    'id',
                    'name',
                    'description',
                ],
            ],
        ]);
    }

    /**
     * Test function to verify nb participation
     */
    public function testNbParticipation()
    {

        GroupParticipation::create([
            'user_id' => 997,
            'group_id' => 997,
            'status' => 'register',
        ]);

        GroupParticipation::create([
            'user_id' => 998,
            'group_id' => 998,
            'status' => 'register',
        ]);
        $numberOfRecordsInDatabase = GroupParticipation::count();
        $response = $this->get('/api/groupParticipation');

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount($numberOfRecordsInDatabase, $responseData);

    }

    /**
     * Test function to verify the waiting count when no data is present.
     *
     * This test checks the behavior of the 'waiting-count' API endpoint
     * when there is no data available for group participation.
     * It ensures that the endpoint returns a status code of 200 and a response
     * containing 'waitingCount' set to 0.
     */
    public function testGetWaitingCountWithNoData()
    {
        GroupParticipation::truncate();
        $response = $this->get('/api/waiting-count');
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals(['waitingCount' => 0], $responseData);
    }

    /**
     * Test function to verify the waiting count when data is present.
     *
     * This test checks the behavior of the 'waiting-count' API endpoint
     * when there is existing data for group participation with a 'waiting' status.
     * It ensures that the endpoint returns a status code of 200 and the correct
     * count of participants with 'waiting' status.
     */
    public function testGetWaitingCountWithData()
    {
        GroupParticipation::create([
            'user_id' => 999,
            'group_id' => 999,
            'status' => 'waiting',
        ]);

        GroupParticipation::create([
            'user_id' => 1000,
            'group_id' => 1000,
            'status' => 'waiting',
        ]);

        GroupParticipation::create([
            'user_id' => 1001,
            'group_id' => 1001,
            'status' => 'waiting',
        ]);

        $response = $this->get('/api/waiting-count');
        $response->assertStatus(200);
        $responseData = $response->json();
        $waitingCountInResponse = $responseData['waitingCount'];
        $waitingCountInDatabase = GroupParticipation::where('status', 'waiting')->count();
        //dd($waitingCountInDatabase);
        $this->assertEquals($waitingCountInDatabase, $waitingCountInResponse);
    }


    /**
     * Test function to verify successful unregistration from a group.
     */
    public function testSuccessfulUnregistrationFromGroup()
    {

        $userId = 1002;
        $groupId = 1002;
        GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'status' => 'register',
        ]);

        $response = $this->post('/api/groups/unregister', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals('User successfully unregistered from the group', $responseData['message']);

        $this->assertDatabaseMissing('group_participations', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);
    }

    /**
     * Test function to verify that a user does not belong to a group.
     */
    public function testUserNotBelongsToGroup()
    {
        $userId = 1004;
        $groupId = 1004;
        // Appeler la méthode unregisterToGroup avec des identifiants d'utilisateur et de groupe où la participation n'existe pas
        $response = $this->post('/api/groups/unregister', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);

        $response->assertStatus(200); // Assurez-vous que le statut de la réponse est 200 (OK)
        $responseData = $response->json();
        $this->assertEquals('The user does not belong to this group', $responseData['error']);
    }

    /**
     * Test function to verify successful update of user participation status.
     */
    public function testSuccessfulUpdateUserParticipation()
    {
        $userId = 1005;
        $groupId = 1005;
        GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'status' => 'waiting',
        ]);

        $response = $this->post('/api/updateUserParticipation', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals('User accepted successfully !', $responseData['message']);

        $this->assertDatabaseHas('group_participations', [
            'user_id' => $userId,
            'group_id' => $groupId,
            'status' => 'register',
        ]);
    }

    /**
     * Test function to verify failed update of user participation.
     */
    public function testFailedUpdateUserParticipation()
    {
        // Appeler la méthode updateUserParticipation avec des identifiants d'utilisateur et de groupe où la participation n'existe pas
        $response = $this->post('/api/updateUserParticipation', [
            'user_id' => 1006, // Utilisateur inexistant pour le groupe
            'group_id' => 1006, // Groupe inexistant
        ]);

        $response->assertStatus(500); // Assurez-vous que le statut de la réponse est 500 pour une erreur interne du serveur
        $responseData = $response->json();
        $this->assertEquals('An error occurred while updating the user participation.', $responseData['error']);
    }

    /**
     * Test function to verify successful rejection of user participation.
     */
    public function testSuccessfulRejectUserParticipation()
    {
        $userId = 1007;
        $groupId = 1007;
        GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'status' => 'waiting',
        ]);

        $response = $this->post('/api/removeUserParticipation', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals('User rejected successfully !', $responseData['message']);

        $this->assertDatabaseMissing('group_participations', [
            'user_id' => $userId,
            'group_id' => $groupId,
        ]);
    }

    /**
     * Test function to verify failed rejection of user participation.
     */
    public function testFailedRejectUserParticipation()
    {
        $response = $this->post('/api/removeUserParticipation', [
            'user_id' => 1008,
            'group_id' => 1008,
        ]);

        $response->assertStatus(500);
        $responseData = $response->json();
        $this->assertEquals('An error occurred while rejected the user participation.', $responseData['error']);
    }

    /**
     * Test function to retrieve groups by user ID.
     */
    public function testGetGroupsByUserId()
    {

        $userId = 1009;
        $group1 = 1009;
        $group2 = 1010;

        GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $group1,
        ]);

        GroupParticipation::create([
            'user_id' => $userId,
            'group_id' => $group2,
        ]);

        $response = $this->get('/api/groups/user/' . $userId);

        $response->assertStatus(200); 
        $responseData = $response->json();

        $groupIdsInResponse = collect($responseData)->pluck('group_id')->toArray();

        $groupIdsFromDatabase = GroupParticipation::where('user_id', $userId)->pluck('group_id')->toArray();

        $this->assertEquals($groupIdsFromDatabase, $groupIdsInResponse);
    }

}

//Don't work
/*public function testSuccessfulRegistrationToGroup()
{
    $userId = 1002;
    $groupId = 1002;

    // Appeler la méthode registerToGroup pour un nouvel utilisateur et un groupe
    $response = $this->get("/api/groups/$groupId", [
        'userId' => $userId,
    ]);

    //$response->assertStatus(200); // Assurez-vous que le statut de la réponse est 200 pour une inscription réussie
    $responseData = $response->json();
    $this->assertEquals('Registration to the group successful', $responseData['message']);

    // Vérifier que l'utilisateur est bien inscrit au groupe dans la base de données
    $this->assertDatabaseHas('group_participations', [
        'user_id' => $userId,
        'group_id' => $groupId,
        'status' => 'waiting',
    ]);
}*/
