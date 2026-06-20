<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentPromotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPromotionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected AcademicYear $fromYear;
    protected AcademicYear $toYear;
    protected SchoolClass $fromClass;
    protected SchoolClass $toClass;
    protected Section $fromSection;
    protected Section $toSection;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role_id'   => 1,
            'school_id' => 1,
        ]);

        // Create academic years
        $this->fromYear = AcademicYear::create([
            'year'       => '2025-2026',
            'start_date' => '2025-04-01',
            'end_date'   => '2026-03-31',
            'is_active'  => 1,
        ]);

        $this->toYear = AcademicYear::create([
            'year'       => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date'   => '2027-03-31',
            'is_active'  => 0,
        ]);

        // Create classes
        $this->fromClass = SchoolClass::create([
            'name'      => 'Grade 5',
            'school_id' => 1,
        ]);

        $this->toClass = SchoolClass::create([
            'name'      => 'Grade 6',
            'school_id' => 1,
        ]);

        // Create sections
        $this->fromSection = Section::create([
            'name'      => 'Section A',
            'class_id'  => $this->fromClass->id,
            'school_id' => 1,
        ]);

        $this->toSection = Section::create([
            'name'      => 'Section A',
            'class_id'  => $this->toClass->id,
            'school_id' => 1,
        ]);
    }

    /** Helper to create a student in the from-class. */
    protected function createStudent(array $overrides = []): Student
    {
        return Student::create(array_merge([
            'admission_no'       => 'STU-' . rand(1000, 9999),
            'first_name'         => 'Test',
            'last_name'          => 'Student',
            'gender'             => 'Male',
            'dob'                => '2015-01-01',
            'father_name'        => 'Father',
            'current_class_id'   => $this->fromClass->id,
            'current_section_id' => $this->fromSection->id,
            'status'             => 'Active',
            'school_id'          => 1,
        ], $overrides));
    }

    /* ── Index Page ────────────────────────────────── */

    public function test_index_page_loads_with_dynamic_stats()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.promotions.index'));
        $response->assertStatus(200);
        $response->assertViewHas('stats');
        $response->assertSee('Student Promotions');
    }

    /* ── Load Students AJAX ────────────────────────── */

    public function test_load_students_returns_json()
    {
        $this->actingAs($this->admin, 'admin');
        $this->createStudent();
        $this->createStudent(['first_name' => 'Another']);

        $response = $this->getJson(route('admin.promotions.load-students', [
            'class_id' => $this->fromClass->id,
        ]));

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'total', 'students'])
                 ->assertJson(['total' => 2]);
    }

    public function test_load_students_filters_by_section()
    {
        $this->actingAs($this->admin, 'admin');
        $this->createStudent();

        $otherSection = Section::create([
            'name'      => 'Section B',
            'class_id'  => $this->fromClass->id,
            'school_id' => 1,
        ]);
        $this->createStudent([
            'first_name'         => 'Other',
            'current_section_id' => $otherSection->id,
        ]);

        $response = $this->getJson(route('admin.promotions.load-students', [
            'class_id'   => $this->fromClass->id,
            'section_id' => $this->fromSection->id,
        ]));

        $response->assertJson(['total' => 1]);
    }

    /* ── Get Sections AJAX ─────────────────────────── */

    public function test_get_sections_returns_sections_for_class()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->getJson(route('admin.promotions.get-sections', [
            'class_id' => $this->fromClass->id,
        ]));

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'sections']);
    }

    /* ── Execute Promotion ─────────────────────────── */

    public function test_successful_bulk_promotion()
    {
        $this->actingAs($this->admin, 'admin');
        $student1 = $this->createStudent(['first_name' => 'Alice']);
        $student2 = $this->createStudent(['first_name' => 'Bob']);

        $response = $this->postJson(route('admin.promotions.execute'), [
            'student_ids'           => [$student1->id, $student2->id],
            'from_academic_year_id' => $this->fromYear->id,
            'to_academic_year_id'   => $this->toYear->id,
            'from_class_id'         => $this->fromClass->id,
            'to_class_id'           => $this->toClass->id,
            'to_section_id'         => $this->toSection->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        $data = $response->json('summary');
        $this->assertEquals(2, $data['success']);
        $this->assertEquals(0, $data['failed']);

        // Verify students were moved
        $this->assertEquals($this->toClass->id, $student1->fresh()->current_class_id);
        $this->assertEquals($this->toClass->id, $student2->fresh()->current_class_id);

        // Verify promotion records created
        $this->assertEquals(2, StudentPromotion::where('status', 'success')->count());
    }

    public function test_duplicate_promotion_is_skipped()
    {
        $this->actingAs($this->admin, 'admin');
        $student = $this->createStudent(['first_name' => 'Alice']);

        // First promotion
        $this->postJson(route('admin.promotions.execute'), [
            'student_ids'           => [$student->id],
            'from_academic_year_id' => $this->fromYear->id,
            'to_academic_year_id'   => $this->toYear->id,
            'from_class_id'         => $this->fromClass->id,
            'to_class_id'           => $this->toClass->id,
            'to_section_id'         => $this->toSection->id,
        ]);

        // Second promotion of same student to same destination
        $response = $this->postJson(route('admin.promotions.execute'), [
            'student_ids'           => [$student->id],
            'from_academic_year_id' => $this->fromYear->id,
            'to_academic_year_id'   => $this->toYear->id,
            'from_class_id'         => $this->fromClass->id,
            'to_class_id'           => $this->toClass->id,
            'to_section_id'         => $this->toSection->id,
        ]);

        // Should be rejected by pre-flight validation
        $response->assertStatus(422);
    }

    public function test_same_class_same_session_is_rejected()
    {
        $this->actingAs($this->admin, 'admin');
        $student = $this->createStudent();

        $response = $this->postJson(route('admin.promotions.execute'), [
            'student_ids'           => [$student->id],
            'from_academic_year_id' => $this->fromYear->id,
            'to_academic_year_id'   => $this->fromYear->id, // same session
            'from_class_id'         => $this->fromClass->id,
            'to_class_id'           => $this->fromClass->id, // same class
            'to_section_id'         => $this->fromSection->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_empty_student_ids_is_rejected()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->postJson(route('admin.promotions.execute'), [
            'student_ids'           => [],
            'from_academic_year_id' => $this->fromYear->id,
            'to_academic_year_id'   => $this->toYear->id,
            'from_class_id'         => $this->fromClass->id,
            'to_class_id'           => $this->toClass->id,
        ]);

        $response->assertStatus(422);
    }

    /* ── History Page ──────────────────────────────── */

    public function test_history_page_loads()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.promotions.history'));
        $response->assertStatus(200);
        $response->assertSee('Promotion History');
    }

    /* ── Help Page ─────────────────────────────────── */

    public function test_help_page_loads()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.promotions.help'));
        $response->assertStatus(200);
        $response->assertSee('How to Use Student Promotions');
    }

    /* ── Unauthenticated Access ────────────────────── */

    public function test_unauthenticated_user_is_redirected()
    {
        $response = $this->get(route('admin.promotions.index'));
        $response->assertRedirect();
    }
}
