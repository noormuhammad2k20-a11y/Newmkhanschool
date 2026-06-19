<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings page with all groups and settings.
     */
    public function index()
    {
        $groups = $this->settingService->getAllGrouped();
        return view('admin.settings.index', compact('groups'));
    }

    /**
     * Update a single setting (AJAX).
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'key'   => 'required|string|max:191',
            'value' => 'nullable',
        ]);

        try {
            $this->settingService->set($request->key, $request->value);

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update all settings in a group (AJAX).
     */
    public function updateGroup(Request $request, string $slug): JsonResponse
    {
        try {
            $settings = $request->except('_token');
            $count = $this->settingService->updateMany($settings);

            return response()->json([
                'success' => true,
                'message' => "{$count} setting(s) updated successfully.",
                'count'   => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle file upload for a setting (AJAX).
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate([
            'key'  => 'required|string|max:191',
            'file' => 'required|file|max:2048|mimes:jpg,jpeg,png,svg,ico,webp',
        ]);

        try {
            $path = $this->settingService->uploadFile($request->key, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully.',
                'path'    => $path,
                'url'     => asset('storage/' . $path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear settings cache (AJAX).
     */
    public function clearCache(): JsonResponse
    {
        try {
            $this->settingService->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Settings cache cleared successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export all settings as JSON.
     */
    public function export()
    {
        $data = $this->settingService->export();

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="settings_export_' . date('Y-m-d') . '.json"');
    }

    /**
     * Import settings from JSON file (AJAX).
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:1024',
        ]);

        try {
            $content = file_get_contents($request->file('file')->getRealPath());
            $data = json_decode($content, true);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON file.',
                ], 422);
            }

            $count = $this->settingService->import($data);

            return response()->json([
                'success' => true,
                'message' => "{$count} setting(s) imported successfully.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
