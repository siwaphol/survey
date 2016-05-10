<?php

use Illuminate\Database\Seeder;

class QuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(OptionTableSeeder::class);
        $this->call(QuestionTableSeeder::class);
    }
}

class OptionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('options')->insert([
            'name'=>'อื่นๆ'
        ]);
    }
}

class QuestionTableSeeder extends Seeder
{
    public function run()
    {
        $question = \App\Question::create([
            'parent_id'=>null,
            'sibling_order'=>1,
            'section'=>'ก.1',
            'input_type'=>'select',
            'name'=>'ลักษณะที่อยู่อาศัย',
            'subtext'=>'เลือกตอบได้เพียงคำตอบเดียว'
        ]);
        $option = \App\Option::create(['name'=>'ตึก เช่น บ้านปูน คอนกรีต อิฐ บล็อก']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'บ้านครึ่งตึกครึ่งไม้']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'บ้านที่ใช้วัสดุถาวรเป็นส่วนใหญ่ เช่น บ้านไม้']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'บ้านที่ใช้วัสดุไม่ถาวร เช่น บ้านใบจาก ฟาง แฝก']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'บ้านที่ใช้วัสดุใช้แล้วอยู่ในสภาพผุพัง']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'บ้านที่ยังสร้างไม่เสร็จ']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>1
        ]);

        $question = \App\Question::create([
            'parent_id'=>null,
            'sibling_order'=>2,
            'section'=>'ก.1',
            'input_type'=>'select',
            'name'=>'ประเภทที่อยู่อาศัย',
            'subtext'=>'เลือกตอบได้เพียงคำตอบเดียว'
        ]);
        $option = \App\Option::create(['name'=>'บ้านเดี่ยว']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'ทาวน์เฮ้าส์/ทาวน์โฮม/บ้านแฝด']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'ตึกแถว/ห้องแถว/อาคารพาณิชย์']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        $option = \App\Option::create(['name'=>'คอนโดฯ/แฟลต/อพาร์ทเม้นท์']);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>$option->id
        ]);
        DB::table('option_questions')->insert([
            'question_id'=>$question->id,
            'option_id'=>1
        ]);
    }
}

