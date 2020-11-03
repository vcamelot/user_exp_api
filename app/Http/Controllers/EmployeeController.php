<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Experience;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\SearchEmployeeRequest;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EmployeeController extends Controller
{
    /**
     * Get all employees
     * 
     * @return array
     */
    public function index()
    {
        $employees = Employee::with('experience')->get();
        $count = $employees->count();
        if ($count == 0) {
            return [
                'result' => 'error',
                'message' => 'No employees found',
                'data' => []
            ];
        } else {
            return [
                'result' => 'success',
                'message' => $count . ' employee(s) found',
                'data' => $employees,
            ];
        }
    }

    /**
     * Find employee by ID
     * 
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $employee = Employee::where('id', $id)->with('experience')->first();
        if (is_null($employee)) {
            return [
                'result' => 'error',
                'message' => 'Employee not found',
                'data' => []
            ];
        }

        return [
            'result' => 'success',
            'message' => 'Employee found',
            'data' => $employee,
        ];
    }

    /**
     * Create new employee and experience
     * 
     * @return array
     */
    public function store(StoreEmployeeRequest $request)
    {
        // Send request to RandomUser API to get fake employee data
        $client = new Client();
        $url = "https://randomuser.me/api/";

        try {
            $response = $client->request("GET", $url);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return [
                    'result' => 'error',
                    'message' => Psr7\Message::toString($e->getResponse())
                ];
            }
        }

        $random_data = json_decode((string)$response->getBody())->results[0];

        // Create new employee
        $employee = new Employee();
        $employee['gender'] = $random_data->gender;
        $employee['first_name'] = $random_data->name->first;
        $employee['last_name'] = $random_data->name->last;
        $employee['title'] = $random_data->name->title;
        $employee['address'] = implode(
            " ",
            [
                $random_data->location->street->number,
                $random_data->location->street->name,
            ]
        );
        $employee['city'] = $random_data->location->city;
        $employee['state'] = $random_data->location->state;
        $employee['country'] = $random_data->location->country;
        $employee['postcode'] = $random_data->location->postcode;
        $employee['email'] = $random_data->email;
        $employee->save();
        $employee_id = $employee->id;

        $data = $request->all();

        // Create employee experience
        foreach ($data['experience'] as $row) {
            $experience = new Experience([
                'employee_id' => $employee_id,
                'company_name' => $row['company_name'],
                'job_title' => $row['job_title'],
                'experience' => $row['experience'],
                'month_from' => $row['month_from'],
                'year_from' => $row['year_from'],
                'month_to' => $row['month_to'] ?? null,
                'year_to' => $row['year_to'] ?? null
            ]);
            $experience->save();
        }

        return [
            'result' => 'success',
            'message' => 'New employee and experience created',
            'data' => [
                'employee_id' => $employee_id,
                'first_name' => $employee['first_name'],
                'last_name' => $employee['last_name'],
                'email' => $employee['email']
            ]
        ];
    }

    /**
     * Search employee by first or last name
     * 
     * @return array
     */
    public function search(SearchEmployeeRequest $request)
    {
        $data = Employee::where('first_name', 'like', '%' . $request->text . '%')
            ->orWhere('last_name', 'like', '%' . $request->text . '%')
            ->with('experience')
            ->get();

        if (count($data) == 0) {
            return [
                'result' => 'error',
                'message' => 'No employees found',
                'data' => []
            ];
        } else {
            return [
                'result' => 'success',
                'message' => count($data) . ' employee(s) found',
                'data' => $data
            ];
        }
    }
}
