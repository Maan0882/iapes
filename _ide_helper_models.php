<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $intern_id
 * @property string $date
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereInternId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models\InternManagement{
/**
 * @property int $id
 * @property string|null $intern_code
 * @property int|null $application_id
 * @property int|null $offer_letter_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $joining_date
 * @property string|null $project_name
 * @property string|null $project_description
 * @property string|null $completion_letter_template
 * @property int $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterviewManagement\Application|null $application
 * @property-read \App\Models\InternManagement\InternshipBatch|null $batch
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\InterviewManagement\OfferLetter|null $offer_letters
 * @property-read \App\Models\InterviewManagement\OfferLetter|null $offerletter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskManagement\TaskSubmission> $submissions
 * @property-read int|null $submissions_count
 * @property-read \App\Models\InternManagement\InternTeam|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Intern> $teammates
 * @property-read int|null $teammates_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereCompletionLetterTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereInternCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereOfferLetterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereProjectDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intern whereUsername($value)
 */
	class Intern extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

namespace App\Models\InternManagement{
/**
 * @property int $id
 * @property int $internship_batch_id
 * @property string $team_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\InternshipBatch|null $batch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InternManagement\Intern> $interns
 * @property-read int|null $interns_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam whereInternshipBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam whereTeamName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternTeam whereUpdatedAt($value)
 */
	class InternTeam extends \Eloquent {}
}

namespace App\Models\InternManagement{
/**
 * @property int $id
 * @property string $batch_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $batch_timing
 * @property int $no_of_interns
 * @property int|null $team_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InternManagement\Intern> $interns
 * @property-read int|null $interns_count
 * @property-read \App\Models\InternManagement\InternTeam|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InternManagement\InternTeam> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereBatchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereBatchTiming($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereNoOfInterns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternshipBatch whereUpdatedAt($value)
 */
	class InternshipBatch extends \Eloquent {}
}

namespace App\Models\InterviewManagement{
/**
 * @property int $id
 * @property string|null $application_code
 * @property string|null $verification_token
 * @property string $email
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $college
 * @property string|null $degree
 * @property string|null $year
 * @property numeric|null $cgpa
 * @property string|null $domain
 * @property int|null $duration
 * @property string $duration_unit
 * @property string|null $skills
 * @property string|null $resume_path
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @property-read \App\Models\InterviewManagement\OfferLetter|null $offerLetter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InterviewManagement\OfferLetter> $offer_letters
 * @property-read int|null $offer_letters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereApplicationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCgpa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCollege($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereDurationUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereResumePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereYear($value)
 */
	class Application extends \Eloquent {}
}

namespace App\Models\InterviewManagement{
/**
 * @property int $id
 * @property string $assignment_code
 * @property int $interview_batch_id
 * @property int $application_id
 * @property string|null $attendance
 * @property int|null $problem_solving
 * @property int|null $communication
 * @property numeric|null $overall_score
 * @property string|null $remarks
 * @property string $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterviewManagement\Application|null $application
 * @property-read \App\Models\InterviewManagement\InterviewBatch|null $batch
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereAssignmentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereCommunication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereInterviewBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereOverallScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereProblemSolving($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewAssignment whereUpdatedAt($value)
 */
	class InterviewAssignment extends \Eloquent {}
}

namespace App\Models\InterviewManagement{
/**
 * @property int $id
 * @property string $interview_batch_code
 * @property string $interview_batch_name
 * @property string $interview_date
 * @property string $start_time
 * @property string $end_time
 * @property string $interview_location
 * @property int $batch_size
 * @property string $capacity_status
 * @property string $workflow_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InterviewManagement\InterviewAssignment> $assignments
 * @property-read int|null $assignments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereBatchSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereCapacityStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereInterviewBatchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereInterviewBatchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereInterviewDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereInterviewLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterviewBatch whereWorkflowStatus($value)
 */
	class InterviewBatch extends \Eloquent {}
}

namespace App\Models\InterviewManagement{
/**
 * @property int $id
 * @property string $offer_letter_code
 * @property int|null $application_id
 * @property int|null $intern_id
 * @property string|null $name
 * @property string|null $college
 * @property string|null $university
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon $joining_date
 * @property string $completion_date
 * @property string $internship_role
 * @property string|null $internship_position
 * @property string $working_hours
 * @property string|null $template
 * @property string|null $description
 * @property int $is_accepted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterviewManagement\Application|null $application
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereCollege($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereCompletionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereInternId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereInternshipPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereInternshipRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereOfferLetterCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereUniversity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfferLetter whereWorkingHours($value)
 */
	class OfferLetter extends \Eloquent {}
}

namespace App\Models\TaskManagement{
/**
 * @property int $task_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $attachment
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskManagement\TaskAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskManagement\TaskSubmission> $submissions
 * @property-read int|null $submissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereUpdatedAt($value)
 */
	class Task extends \Eloquent {}
}

namespace App\Models\TaskManagement{
/**
 * @property int $task_assignment_id
 * @property int $task_id
 * @property string $assigned_type
 * @property int|null $intern_id
 * @property int|null $team_id
 * @property int|null $batch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\InternshipBatch|null $batch
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @property-read \App\Models\TaskManagement\Task|null $task
 * @property-read \App\Models\TaskManagement\TaskSubmission|null $task_submission
 * @property-read \App\Models\InternManagement\InternTeam|null $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereAssignedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereInternId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereTaskAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereUpdatedAt($value)
 */
	class TaskAssignment extends \Eloquent {}
}

namespace App\Models\TaskManagement{
/**
 * @property int $submission_id
 * @property int $task_id
 * @property int $intern_id
 * @property string|null $submission_text
 * @property string|null $submission_file
 * @property string|null $submitted_at
 * @property string $status
 * @property string|null $admin_feedback
 * @property int|null $marks
 * @property string|null $grade
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @property-read \App\Models\TaskManagement\Task|null $task
 * @property-read \App\Models\TaskManagement\TaskAssignment|null $taskAssignment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereAdminFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereInternId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereMarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereSubmissionFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereSubmissionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskSubmission whereUpdatedAt($value)
 */
	class TaskSubmission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternManagement\Intern|null $intern
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

