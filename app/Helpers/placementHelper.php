<?php


namespace App\Helpers;


use Illuminate\Support\Facades\DB;

class placementHelper
{
    public static function getLeftMembersRecursive($parentId = null, &$ltot = '')
    {
        $query = DB::table('member_binary');

        if (is_null($parentId)) {
            $query->whereNull('parent');
        } else {
            $query->where('parent', $parentId);
        }

        $results = $query->get();

        foreach ($results as $row) {
            $memberId = $row->memid;

            // Recurse down the tree
            self::getLeftMembersRecursive($memberId, $ltot);


           // echo("Member ID: $memberId\n"); // Debugging output
            // Append the member ID to the list
            $ltot .= ',' . $memberId;
        }
    }

     public static function getRightMembersRecursive($parentId = null, &$rtot = '')
    {
        $query = DB::table('member_binary');

        if (is_null($parentId)) {
            $query->whereNull('parent');
        } else {
            $query->where('parent', $parentId);
        }

        $results = $query->get();

        foreach ($results as $row) {
            $memberId = $row->memid;

            // Recurse down the tree
            self::getRightMembersRecursive($memberId, $rtot);


           // echo("Member ID: $memberId\n"); // Debugging output
            // Append the member ID to the list
            $rtot .= ',' . $memberId;
        }
    }
     public static function getAllMembersRecursive($parentId = null, &$atot = '')
    {
        $query = DB::table('member_binary');

        if (is_null($parentId)) {
            $query->whereNull('parent');
        } else {
            $query->where('parent', $parentId);
        }

        $results = $query->get();

        foreach ($results as $row) {
            $memberId = $row->memid;

            // Recurse down the tree
            self::getAllMembersRecursive($memberId, $atot);


           // echo("Member ID: $memberId\n"); // Debugging output
            // Append the member ID to the list
            $atot .= ',' . $memberId;
        }
    }





}
      