<?php
namespace App\Controllers;

use App\Models\WorkoutHistory;

class WorkoutHistoryController {

    private $workoutHistoryModel;

    public function __construct() {
        $this->workoutHistoryModel = new WorkoutHistory();
    }


    public function getRecentForDashboard($userId, $limit = 5) {
        return $this->workoutHistoryModel
            ->getRecentWorkouts($userId, $limit);
    }

    public function getTodayCaloriesBurned($userId) {
        return $this->workoutHistoryModel
            ->getTodayCaloriesBurned($userId);
    }

    public function getStats($userId) {
        return $this->workoutHistoryModel
            ->getWorkoutStats($userId);
    }

    public function getStreak($userId) {
        return $this->workoutHistoryModel
            ->getWorkoutStreak($userId);
    }
}
