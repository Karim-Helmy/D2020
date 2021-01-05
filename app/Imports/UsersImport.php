<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Hash;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validator::make($row, [
        //     '0' => 'required|min:6',
        //     '1' => 'required|unique:users,username',
        //     '2' => 'required|min:6',
        //     '3' => 'required|in:2,3,4',
        // ])->validate();

        return new User([
          'name'          => $row['name'],
          'username'      => $row['username'],
          'password'      => $row['password'],
          'email'         => $row['email'],
          'mobile'        => $row['mobile'],
          'id_number'     => $row['id_number'],
          'type'          => $row['type'],
          // 'subscriber_id' => auth()->user()->subscriber_id,
          // 'status' => '1',
        ]);
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
