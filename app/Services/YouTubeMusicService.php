<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class YouTubeMusicService
{
    protected string $scriptPath;

    public function __construct()
    {
        $this->scriptPath = base_path('python/ytmusic_wrapper.py');
    }

    protected function runCommand(string ...$args): array
    {
        $pythonPath = config('services.youtube_music.python_path');
        $pythonPath = $pythonPath ? base_path($pythonPath) : base_path('python/.venv/bin/python3');
        $cmd = array_merge([$pythonPath, $this->scriptPath], $args);

        $result = Process::run($cmd);

        if ($result->failed()) {
            $error = $result->errorOutput() ?: $result->output() ?: 'Unknown error';
            return [
                'success' => false,
                'error' => trim($error),
                'exitCode' => $result->exitCode(),
            ];
        }

        $output = json_decode($result->output(), true);
        if ($output === null && json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Failed to parse response: ' . $result->output(),
            ];
        }

        return $output;
    }

    public function search(string $query): array
    {
        return $this->runCommand('search', $query);
    }

    public function getSong(string $videoId): array
    {
        return $this->runCommand('get-song', $videoId);
    }

    public function downloadAudio(string $videoId, string $outputPath): array
    {
        return $this->runCommand('download-audio', $videoId, $outputPath);
    }

    public function authStatus(): array
    {
        return $this->runCommand('auth-status');
    }
}
