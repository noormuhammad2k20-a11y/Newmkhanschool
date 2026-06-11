<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCertificatesSeeder extends Seeder
{
    public function run()
    {
        $transfer = <<<HTML
<div style="text-align:center; padding: 20px;">
    <h1 style="color: #1a237e; font-size: 36px; margin-bottom: 5px; text-transform: uppercase; font-family: 'Times New Roman', serif;">{{school_name}}</h1>
    <div style="width: 150px; height: 3px; background-color: #b8860b; margin: 0 auto 20px auto;"></div>
    
    <h2 style="color: #444; font-size: 26px; letter-spacing: 2px; margin-bottom: 40px; font-weight: bold; font-family: 'Helvetica', sans-serif;">TRANSFER CERTIFICATE</h2>
    
    <div style="font-size: 18px; line-height: 2; text-align: justify; margin: 0 40px;">
        <p style="text-indent: 40px;">
            This is to certify that <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{student_name}}</strong>, 
            Son/Daughter of <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{father_name}}</strong>, 
            bearing Admission No. <strong style="color: #333;">{{admission_no}}</strong>, was a bonafide student of this institution from 
            <strong>{{admission_date}}</strong> to <strong>{{leaving_date}}</strong>.
        </p>
        <p style="text-indent: 40px;">
            He/She was studying in Class <strong>{{class_name}}</strong> at the time of leaving. His/Her character and conduct were found to be 
            <strong style="color: #1a237e; font-style: italic;">Good</strong> during his/her stay in this institution.
        </p>
        <p style="text-indent: 40px;">
            No dues are pending against him/her. We wish him/her all the best for future endeavors.
        </p>
    </div>
</div>
HTML;

        $character = <<<HTML
<div style="text-align:center; padding: 20px;">
    <h1 style="color: #1a237e; font-size: 36px; margin-bottom: 5px; text-transform: uppercase; font-family: 'Times New Roman', serif;">{{school_name}}</h1>
    <div style="width: 150px; height: 3px; background-color: #b8860b; margin: 0 auto 20px auto;"></div>
    
    <h2 style="color: #444; font-size: 26px; letter-spacing: 2px; margin-bottom: 40px; font-weight: bold; font-family: 'Helvetica', sans-serif;">CHARACTER CERTIFICATE</h2>
    
    <div style="font-size: 18px; line-height: 2; text-align: justify; margin: 0 40px;">
        <p style="text-indent: 40px;">
            This is to certify with great pleasure that <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{student_name}}</strong>, 
            Son/Daughter of <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{father_name}}</strong>, 
            bearing Admission No. <strong style="color: #333;">{{admission_no}}</strong>, has been a regular student of Class 
            <strong>{{class_name}}</strong> during the academic session <strong>{{academic_year}}</strong>.
        </p>
        <p style="text-indent: 40px;">
            His/Her character, conduct, and moral behavior have been <strong style="color: #1a237e; font-style: italic; font-size: 20px;">Excellent</strong> throughout his/her academic career. 
            He/She is a diligent, well-mannered, and responsible student with active participation in academic and co-curricular activities.
        </p>
        <p style="text-indent: 40px; margin-top: 20px; text-align: center; font-style: italic; font-family: 'Times New Roman', serif; font-size: 22px; color: #555;">
            "We wish him/her all the best and a bright future."
        </p>
    </div>
</div>
HTML;

        $bonafide = <<<HTML
<div style="text-align:center; padding: 20px;">
    <h1 style="color: #1a237e; font-size: 36px; margin-bottom: 5px; text-transform: uppercase; font-family: 'Times New Roman', serif;">{{school_name}}</h1>
    <div style="width: 150px; height: 3px; background-color: #b8860b; margin: 0 auto 20px auto;"></div>
    
    <h2 style="color: #444; font-size: 26px; letter-spacing: 2px; margin-bottom: 40px; font-weight: bold; font-family: 'Helvetica', sans-serif;">BONAFIDE CERTIFICATE</h2>
    
    <div style="font-size: 18px; line-height: 2; text-align: justify; margin: 0 40px;">
        <p style="text-indent: 40px;">
            This is to certify that <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{student_name}}</strong>, 
            Son/Daughter of <strong style="font-size: 20px; font-family: 'Times New Roman', serif; color: #1a237e;">{{father_name}}</strong>, 
            Resident of <strong style="font-style: italic;">{{address}}</strong>, bearing Admission No. <strong style="color: #333;">{{admission_no}}</strong>, 
            is currently a bonafide and regular student of this institution.
        </p>
        <p style="text-indent: 40px;">
            He/She is actively pursuing studies in Class <strong>{{class_name}}</strong> for the academic session <strong>{{academic_year}}</strong>.
        </p>
        <p style="text-indent: 40px;">
            This certificate is being issued at the request of the student/parent for the purpose of 
            <strong style="text-decoration: underline; color: #1a237e;">{{purpose}}</strong>.
        </p>
    </div>
</div>
HTML;

        DB::table('document_templates')->where('slug', 'transfer-certificate')->update(['content' => $transfer, 'design_type' => 'elegant']);
        DB::table('document_templates')->where('slug', 'character-certificate')->update(['content' => $character, 'design_type' => 'elegant']);
        DB::table('document_templates')->where('slug', 'bonafide-certificate')->update(['content' => $bonafide, 'design_type' => 'elegant']);
    }
}
