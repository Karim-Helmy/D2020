<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use App\CourseUser;

class CoursesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
    }
    public function batchSize(): int
   {
       return 1000;
   }

   public function chunkSize(): int
   {
       return 1000;
   }

}
