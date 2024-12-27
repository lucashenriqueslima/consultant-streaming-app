<?php

namespace App\Livewire\Candidate;

use App\Enums\CandidateStatus;
use App\Enums\Panel;
use App\Models\Candidate;
use App\Models\Course;
use App\Models\CandidateProgress;
use App\Services\CandidateService;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;


class Home extends Component
{
    public Collection $courses;
    public ?int $lessonsCount;
    public bool $hasCompletedAllLessons = false;

    //mount
    public function mount()
    {
        $this->courses = Course::with([
            'lessons' => function ($query) {
                $query->orderBy('order')
                    ->with(['candidateProgress' => function ($query) {
                        $query->where('candidate_id', Auth::guard('candidate')->id());
                    }]);
            }
        ])
            ->where('panel', Panel::Candidate)
            ->get();

        $this->lessonsCount = Course::where('panel', Panel::Candidate)
            ->withCount('lessons')
            ->get()
            ->sum('lessons_count');

        $this->hasCompletedAllLessons = $this->hasCompletedAllLessons();
    }

    #[On('lessonStarted')]
    public function createCandidateProgressIfDontExist(string $lessonId): void
    {
        CandidateProgress::firstOrCreate(
            [
                'candidate_id' => Auth::guard('candidate')->id(),
                'lesson_id' => $lessonId,
            ],
            [
                'started_at' => now(),
                'is_completed' => false,
            ]
        );
    }

    #[On('lessonCompleted')]
    public function updateCandidateProgress(string $lessonId): void
    {
        CandidateProgress::where('candidate_id', Auth::guard('candidate')->id())
            ->where('lesson_id', $lessonId)
            ->update([
                'completed_at' => now(),
                'is_completed' => true,
            ]);

        if ($this->hasCompletedAllLessons()) {
            $this->showAllLessonsCompletedNotification();
        }
    }

    public function hasCompletedAllLessons(): bool
    {
        if (!$this->lessonsCount) {
            return true;
        }

        $completedLessonsCount = CandidateProgress::where('candidate_id', Auth::guard('candidate')->id())
            ->where('is_completed', true)
            ->count();

        if ($completedLessonsCount == $this->lessonsCount) {
            $candidate = Candidate::find(Auth::guard('candidate')->id());

            if ($candidate->status !== CandidateStatus::COMPLETED_LESSONS) {
                $candidateService = new CandidateService();
                $candidateService->update($candidate, ['status' => CandidateStatus::COMPLETED_LESSONS]);
            }

            return true;
        }

        return false;
    }


    public function showAllLessonsCompletedNotification(): void
    {
        Notification::make()
            ->title('Parabéns, todas as aulas foram concluidas!')
            ->success()
            ->persistent()
            ->body('Entraremos em contato em breve, para realização de uma avaliação de desempenho.')
            ->send();
    }

    public function getStorageUrl(string $path): string
    {
        return Storage::url($path);
    }

    public function getCandidateProgressStatusLabel(?bool $isCompleted): string
    {
        return match ($isCompleted) {
            true => 'Completo',
            false => 'Em Progresso',
            default => 'Não Iniciado',
        };
    }

    public function getCandidateProgressStatusIcon(?bool $isCompleted): string
    {
        return match ($isCompleted) {
            true => 'heroicon-o-check',
            false => 'heroicon-o-arrow-path',
            default => 'heroicon-o-x-circle',
        };
    }

    public function render()
    {
        if ($this->hasCompletedAllLessons()) {
            $this->showAllLessonsCompletedNotification();
        }

        return view(
            'livewire.candidate.home',
            [
                'courses' => $this->courses,
            ]
        );
    }
}
