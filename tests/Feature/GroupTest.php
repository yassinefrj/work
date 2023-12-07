<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{

    use RefreshDatabase;

    public function test_insert()
    {
        // Créez un groupe de test
        $groupName = 'Test Group';
        $groupDescription = 'This is a test group description';

        Group::create([
            'name' => $groupName,
            'description' => $groupDescription,
        ]);

        // Récupérez le groupe depuis la base de données
        $group = Group::where('name', $groupName)->first();

        // Vérifiez que le groupe a été créé correctement
        $this->assertEquals($groupName, $group->name);
        $this->assertEquals($groupDescription, $group->description);
    }


    public function test_get_all_groups()
    {
        // Créez quelques groupes de test dans la base de données

        // AYOUB : j'ai commenté parce que c'est déjà seedé
        /*Group::create([
            'name' => 'Group 1',
            'description' => 'Description 1',
        ]);

        Group::create([
            'name' => 'Group 2',
            'description' => 'Description 2',
        ]);*/

        // Appelez la méthode pour récupérer tous les groupes
        $groups = Group::all();

        // Assurez-vous que la méthode renvoie une instance de la collection Eloquent
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $groups);

        // Vérifiez que le nombre de groupes récupérés correspond au nombre de groupes que vous avez créés
        $this->assertCount(2, $groups);

        // Vérifiez que les noms des groupes correspondent à ceux que vous avez créés
        $this->assertEquals('Group 1', $groups[0]->name);
        $this->assertEquals('Group 2', $groups[1]->name);

        // Vérifiez que les descriptions des groupes correspondent
        $this->assertEquals('Description de la group 1', $groups[0]->description);
        $this->assertEquals('Description de la group 2', $groups[1]->description);
    }

    public function test_validation_rules()
    {
        // Préparez les données de formulaire (vide)
        $formData = [];

        // Appelez votre route ou votre action de contrôleur ici (à adapter à votre code)
        $response = $this->actingAs(User::find(1))->post('/add_group', $formData);

        // Assurez-vous que la réponse contient des erreurs de validation pour "name" et "description"
        $response->assertSessionHasErrors(['name', 'description']);
    }

    public function test_group_creation()
    {
        $response = $this->actingAs(User::find(1))->post('/add_group', [
            'name' => 'Group 3',
            'description' => 'This is a test',
        ]);

        $response->assertStatus(302);
    }

    public function test_consult()
    {
        $response = $this->actingAs(User::find(1))->get('/groups');

        $response->assertStatus(200);
    }

    public function test_consult_one_group()
    {
        $response = $this->actingAs(User::find(1))->get('/groups', ['name' => 'Group 1']);
        $response->assertStatus(200);
    }

    public function testGroupNameMustBeUnique()
    {
        $uniqueGroupName = 'Unique Group';
        $response = $this->actingAs(User::find(1))->post('/add_group', [
            'name' => $uniqueGroupName,
            'description' => 'Description for the unique group',
        ]);
        $response->assertStatus(302);

        $response = $this->actingAs(User::find(1))->post('/add_group', [
            'name' => $uniqueGroupName,
            'description' => 'Another description',
        ]);
        $response->assertSessionHasErrors('name');
    }



}
