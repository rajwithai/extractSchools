<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Director;
use App\Models\School;

class DirectorController extends Controller
{
    /**
     * Generate password for director
     *
     * @param int $directorId
     * @return string|null
     */
    public function generatePassword($directorId)
    {
        $director = Director::find($directorId);
        if (!$director) {
            return null;
        }

        // Generate a random password
        $password = $this->generateRandomPassword();
        
        // Hash and save the password
        $director->password = Hash::make($password);
        $director->save();

        // Log password generation for security tracking
        \Log::info("Password generated for director ID: {$directorId}");

        return $password;
    }

    /**
     * Generate a random password
     *
     * @param int $length
     * @return string
     */
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * Create director
     *
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        try {
            // Check if email already exists
            if (isset($data['accessemail'])) {
                $existingDirector = Director::where('accessemail', $data['accessemail'])->first();
                if ($existingDirector) {
                    return [
                        'result' => false,
                        'code' => 106,
                        'message' => 'Email already in use'
                    ];
                }
            }

            $director = new Director();
            $director->name = $data['name'] ?? 'Director';
            $director->accessemail = $data['accessemail'] ?? null;
            $director->email = $data['email'] ?? $data['accessemail'] ?? null;
            $director->phone = $data['phone'] ?? null;
            $director->password = Hash::make($data['password'] ?? 'temporal123');
            $director->active = $data['active'] ?? true;
            $director->school_id = $data['school_id'] ?? null;
            $director->save();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'Director created successfully',
                'director_id' => $director->id
            ];

        } catch (\Exception $e) {
            \Log::error('Error creating director: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Update director
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $director = Director::find($id);
            if (!$director) {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'Director not found'
                ];
            }

            // Check if email is being changed and if it already exists
            if (isset($data['accessemail']) && $data['accessemail'] !== $director->accessemail) {
                $existingDirector = Director::where('accessemail', $data['accessemail'])->first();
                if ($existingDirector) {
                    return [
                        'result' => false,
                        'code' => 106,
                        'message' => 'Email already in use'
                    ];
                }
            }

            // Update password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $director->fill($data);
            $director->save();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'Director updated successfully'
            ];

        } catch (\Exception $e) {
            \Log::error('Error updating director: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Get director by ID
     *
     * @param int $id
     * @return Director|null
     */
    public function getDirector($id)
    {
        return Director::with('school')->find($id);
    }

    /**
     * Delete director
     *
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $director = Director::find($id);
            if (!$director) {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'Director not found'
                ];
            }

            // Update school to remove director reference
            if ($director->school_id) {
                $school = School::find($director->school_id);
                if ($school) {
                    $school->director_id = null;
                    $school->save();
                }
            }

            $director->delete();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'Director deleted successfully'
            ];

        } catch (\Exception $e) {
            \Log::error('Error deleting director: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Reset director password
     *
     * @param int $directorId
     * @return array
     */
    public function resetPassword($directorId)
    {
        try {
            $password = $this->generatePassword($directorId);
            
            if ($password) {
                return [
                    'result' => true,
                    'code' => 200,
                    'message' => 'Password reset successfully',
                    'new_password' => $password
                ];
            } else {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'Director not found'
                ];
            }

        } catch (\Exception $e) {
            \Log::error('Error resetting password: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Activate/Deactivate director
     *
     * @param int $id
     * @param bool $active
     * @return array
     */
    public function setActiveStatus($id, $active)
    {
        try {
            $director = Director::find($id);
            if (!$director) {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'Director not found'
                ];
            }

            $director->active = $active;
            $director->save();

            $status = $active ? 'activated' : 'deactivated';
            
            return [
                'result' => true,
                'code' => 200,
                'message' => "Director {$status} successfully"
            ];

        } catch (\Exception $e) {
            \Log::error('Error updating director status: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Get directors list with pagination
     *
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getDirectorsList($page = 1, $perPage = 50)
    {
        $directors = Director::with('school')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'directors' => $directors->items(),
            'pagination' => [
                'current_page' => $directors->currentPage(),
                'last_page' => $directors->lastPage(),
                'per_page' => $directors->perPage(),
                'total' => $directors->total(),
            ]
        ];
    }
} 