<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class Task2Test extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    protected array $modelFields = [
        "fio",
        "group",
        "course"
    ];
    protected string|Student $modelClass = Student::class;
    protected string $modelPluralName = "students";
    protected string $modelSingleName = "student";


    /* Checks json pagination */
    public function testIndex()
    {
        $this->modelClass::factory(50)->create();
        $per_page = rand(5, 15);
        $routeName = $this->modelPluralName . ".index";
        $response = $this->getJson(route($routeName, ['per_page' => $per_page]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(["meta", "links", "data" => [$this->modelFields]]);
        $responseContent = $response->json();
        $this->assertCount($per_page, $responseContent["data"]);
        $this->assertEquals(50, $responseContent["meta"]["total"]);
    }

    /* Checks model creating */
    public function testCreate()
    {
        $routeName = $this->modelPluralName . ".create";
        $response = $this->get(route($routeName));
        $response->assertViewIs($routeName);
        $response->assertSee($this->modelPluralName . " form");
    }

    /* Checks model saving */
    public function testStoreOk()
    {
        $data = $this->modelClass::factory()->make()->toArray();
        $routeName = $this->modelPluralName . ".store";
        $redirectRouteName = $this->modelPluralName . ".show";
        $response = $this->post(route($routeName), $data);
        $response->assertRedirect(route($redirectRouteName, [$this->modelSingleName => 51]));
    }

    /* Checks saving validation */
    public function testStoreError()
    {
        $routeName = $this->modelPluralName . ".store";
        $response = $this->post(route($routeName), []);
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors($this->modelFields);
    }

    /* Checks json model updating */
    public function testUpdateOk()
    {
        $model = $this->modelClass::factory()->create();
        $data = $this->modelClass::factory()->make()->toArray();
        $routeName = $this->modelPluralName . ".update";
        $response = $this->putJson(route($routeName, [$this->modelSingleName => $model->id]), $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => $this->modelFields]);
        $response->assertJsonFragment($data);

    }
    /* Checks json model updating validation */
    public function testUpdateError()
    {
        $model = $this->modelClass::factory()->create();
        $routeName = $this->modelPluralName . ".update";
        $response = $this->putJson(route($routeName, [$this->modelSingleName => $model->id]), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors'=>$this->modelFields]);
    }

}
