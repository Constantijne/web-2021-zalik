<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->query('per_page');
        $paginatedStudents = Student::query()->paginate($per_page);

        return response()->json([
            "meta" => ["total" => $paginatedStudents->total()],
            "links" => [
                "previousPage" => $paginatedStudents->previousPageUrl(),
                "nextPage" => $paginatedStudents->nextPageUrl()
            ],
            "data" => $paginatedStudents->items()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStudentRequest $request
     * @return RedirectResponse
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::create($request->all());

        return redirect()->route('students.show', ["student" => $student]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $student
     * @return JsonResponse
     */
    public function show(int $student)
    {
        $studentModel = Student::find($student);

        if (!$studentModel) {
            throw new NotFoundHttpException('Not found');
        }

        return \response()->json(["data" => $studentModel]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param int $student
     * @return JsonResponse
     */
    public function update(UpdateStudentRequest $request, int $student): JsonResponse
    {
        $studentModel = Student::find($student);

        if (!$studentModel) {
            throw new NotFoundHttpException('Not found');
        }

        $studentModel->fill($request->all())->save();

        return \response()->json(["data" => $studentModel]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
